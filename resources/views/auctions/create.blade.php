<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Your Item - KlikBid</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .image-preview {
            position: relative;
            display: inline-block;
        }
        .remove-image {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }
        .drop-zone {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }
        .drop-zone.dragover {
            border-color: #3b82f6;
            background-color: #dbeafe;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <h1 class="text-3xl font-bold text-blue-600">KlikBid</h1>
                        <span class="ml-2 text-sm text-gray-500">Sri Lanka</span>
                    </a>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ url('/') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Logout</button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Post Your Item</h2>
            <p class="mt-2 text-gray-600">Create your auction listing and reach thousands of potential buyers</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Auction Creation Form -->
        <form method="POST" action="{{ route('auctions.store') }}" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
            @csrf

            <!-- Image Upload Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">📸 Auction Images</h3>
                <p class="text-sm text-gray-600 mb-4">Upload up to 10 high-quality images of your item. The first image will be the main display image.</p>

                <!-- Image Upload Area -->
                <div class="drop-zone bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200" onclick="document.getElementById('images').click()">
                    <div class="space-y-2">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="text-lg font-medium text-gray-900">Click to upload images</div>
                        <div class="text-sm text-gray-500">or drag and drop</div>
                        <div class="text-xs text-gray-400">PNG, JPG, WEBP up to 5MB each (Max 10 images)</div>
                    </div>
                </div>

                <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">

                <!-- Image Preview Area -->
                <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>

                <!-- Image Guidelines -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Image Guidelines</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• First image will be your main display image</li>
                        <li>• Take clear, well-lit photos from multiple angles</li>
                        <li>• Include close-ups of any important details or flaws</li>
                        <li>• Maximum 10 images, 5MB per image</li>
                        <li>• Supported formats: JPG, PNG, WEBP</li>
                    </ul>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h3>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Auction Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g. Toyota Prius 2020 - Excellent Condition" required>
                    <p class="text-xs text-gray-500 mt-1">5-150 characters</p>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select id="category_id" name="category_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->children as $subcategory)
                                    <option value="{{ $subcategory->id }}" {{ old('category_id') == $subcategory->id ? 'selected' : '' }}>
                                        {{ $subcategory->name }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="6"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Provide detailed description of your item including condition, features, history, etc." required>{{ old('description') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">20-10,000 characters. Be detailed to attract more bidders!</p>
                </div>
            </div>

            <!-- Pricing -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Pricing (in LKR)</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Base Price -->
                    <div>
                        <label for="base_price" class="block text-sm font-medium text-gray-700 mb-2">Starting Price *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rs</span>
                            <input type="number" id="base_price" name="base_price" value="{{ old('base_price') }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="100000" min="1" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimum starting bid amount</p>
                    </div>

                    <!-- Reserve Price -->
                    <div>
                        <label for="reserve_price" class="block text-sm font-medium text-gray-700 mb-2">Reserve Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rs</span>
                            <input type="number" id="reserve_price" name="reserve_price" value="{{ old('reserve_price') }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="150000" min="1">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimum price to sell (hidden from bidders)</p>
                    </div>

                    <!-- Buy Now Price -->
                    <div>
                        <label for="buy_now_price" class="block text-sm font-medium text-gray-700 mb-2">Buy Now Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rs</span>
                            <input type="number" id="buy_now_price" name="buy_now_price" value="{{ old('buy_now_price') }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="200000" min="1">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Instant purchase price (optional)</p>
                    </div>
                </div>

                <!-- Deposit Information -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Participation Deposit Information</h4>
                    <p class="text-sm text-blue-800">Bidders will need to pay a refundable deposit to participate:</p>
                    <ul class="text-sm text-blue-700 mt-2 space-y-1">
                        <li>• Rs 100,000+ → Rs 5,000 deposit</li>
                        <li>• Rs 50,000-99,999 → Rs 1,000 deposit</li>
                        <li>• Rs 10,000-49,999 → Rs 500 deposit</li>
                        <li>• Rs 1,000-9,999 → Rs 100 deposit</li>
                        <li>• Rs 100-999 → Rs 50 deposit</li>
                        <li>• Under Rs 100 → No deposit required</li>
                    </ul>
                </div>
            </div>

            <!-- Location (for Land & Properties) -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Location Details</h3>
                <p class="text-sm text-gray-600 mb-4">Required for Land and Properties. Optional for other categories.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address_line" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" id="address_line" name="address_line" value="{{ old('address_line') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g. 123 Galle Road, Colombo 3">
                    </div>

                    <!-- District -->
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                        <select id="district" name="district"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select District</option>
                            <option value="Colombo" {{ old('district') == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                            <option value="Kandy" {{ old('district') == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                            <option value="Gampaha" {{ old('district') == 'Gampaha' ? 'selected' : '' }}>Gampaha</option>
                            <option value="Kalutara" {{ old('district') == 'Kalutara' ? 'selected' : '' }}>Kalutara</option>
                            <option value="Galle" {{ old('district') == 'Galle' ? 'selected' : '' }}>Galle</option>
                            <option value="Matara" {{ old('district') == 'Matara' ? 'selected' : '' }}>Matara</option>
                            <option value="Hambantota" {{ old('district') == 'Hambantota' ? 'selected' : '' }}>Hambantota</option>
                            <option value="Kurunegala" {{ old('district') == 'Kurunegala' ? 'selected' : '' }}>Kurunegala</option>
                            <option value="Puttalam" {{ old('district') == 'Puttalam' ? 'selected' : '' }}>Puttalam</option>
                            <option value="Anuradhapura" {{ old('district') == 'Anuradhapura' ? 'selected' : '' }}>Anuradhapura</option>
                            <option value="Polonnaruwa" {{ old('district') == 'Polonnaruwa' ? 'selected' : '' }}>Polonnaruwa</option>
                            <option value="Matale" {{ old('district') == 'Matale' ? 'selected' : '' }}>Matale</option>
                            <option value="Nuwara Eliya" {{ old('district') == 'Nuwara Eliya' ? 'selected' : '' }}>Nuwara Eliya</option>
                            <option value="Kegalle" {{ old('district') == 'Kegalle' ? 'selected' : '' }}>Kegalle</option>
                            <option value="Ratnapura" {{ old('district') == 'Ratnapura' ? 'selected' : '' }}>Ratnapura</option>
                            <option value="Badulla" {{ old('district') == 'Badulla' ? 'selected' : '' }}>Badulla</option>
                            <option value="Monaragala" {{ old('district') == 'Monaragala' ? 'selected' : '' }}>Monaragala</option>
                            <option value="Ampara" {{ old('district') == 'Ampara' ? 'selected' : '' }}>Ampara</option>
                            <option value="Batticaloa" {{ old('district') == 'Batticaloa' ? 'selected' : '' }}>Batticaloa</option>
                            <option value="Trincomalee" {{ old('district') == 'Trincomalee' ? 'selected' : '' }}>Trincomalee</option>
                            <option value="Vavuniya" {{ old('district') == 'Vavuniya' ? 'selected' : '' }}>Vavuniya</option>
                            <option value="Mannar" {{ old('district') == 'Mannar' ? 'selected' : '' }}>Mannar</option>
                            <option value="Mullaitivu" {{ old('district') == 'Mullaitivu' ? 'selected' : '' }}>Mullaitivu</option>
                            <option value="Kilinochchi" {{ old('district') == 'Kilinochchi' ? 'selected' : '' }}>Kilinochchi</option>
                            <option value="Jaffna" {{ old('district') == 'Jaffna' ? 'selected' : '' }}>Jaffna</option>
                        </select>
                    </div>
                </div>

                <!-- Province -->
                <div class="mt-4">
                    <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                    <select id="province" name="province"
                            class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Province</option>
                        <option value="Western" {{ old('province') == 'Western' ? 'selected' : '' }}>Western</option>
                        <option value="Central" {{ old('province') == 'Central' ? 'selected' : '' }}>Central</option>
                        <option value="Southern" {{ old('province') == 'Southern' ? 'selected' : '' }}>Southern</option>
                        <option value="Northern" {{ old('province') == 'Northern' ? 'selected' : '' }}>Northern</option>
                        <option value="Eastern" {{ old('province') == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                        <option value="North Western" {{ old('province') == 'North Western' ? 'selected' : '' }}>North Western</option>
                        <option value="North Central" {{ old('province') == 'North Central' ? 'selected' : '' }}>North Central</option>
                        <option value="Uva" {{ old('province') == 'Uva' ? 'selected' : '' }}>Uva</option>
                        <option value="Sabaragamuwa" {{ old('province') == 'Sabaragamuwa' ? 'selected' : '' }}>Sabaragamuwa</option>
                    </select>
                </div>
            </div>

            <!-- Auction Schedule -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Auction Schedule</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Start Date/Time -->
                    <div>
                        <label for="start_at" class="block text-sm font-medium text-gray-700 mb-2">Auction Start *</label>
                        <input type="datetime-local" id="start_at" name="start_at" value="{{ old('start_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <p class="text-xs text-gray-500 mt-1">When bidding will begin</p>
                    </div>

                    <!-- End Date/Time -->
                    <div>
                        <label for="end_at" class="block text-sm font-medium text-gray-700 mb-2">Auction End *</label>
                        <input type="datetime-local" id="end_at" name="end_at" value="{{ old('end_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <p class="text-xs text-gray-500 mt-1">When auction will close</p>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 rounded-lg">
                    <h4 class="font-medium text-yellow-900 mb-2">⏰ Important Notes</h4>
                    <ul class="text-sm text-yellow-800 space-y-1">
                        <li>• Your auction will be reviewed by our team before going live</li>
                        <li>• Minimum auction duration is 1 hour</li>
                        <li>• We recommend 3-7 days for best results</li>
                        <li>• All times are in Sri Lanka Time (GMT+5:30)</li>
                    </ul>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    📝 Submit for Approval
                </button>
                <a href="{{ url('/') }}"
                   class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold text-center hover:bg-gray-400 transition duration-200">
                    Cancel
                </a>
            </div>

            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    By submitting this auction, you agree to our
                    <a href="#" class="text-blue-600 hover:underline">Terms & Conditions</a> and
                    <a href="#" class="text-blue-600 hover:underline">Auction Guidelines</a>
                </p>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 KlikBid. All rights reserved.</p>
        </div>
    </footer>

    <script>
        let selectedImages = [];

        function previewImages(input) {
            const files = Array.from(input.files);
            const previewContainer = document.getElementById('imagePreview');

            // Clear existing previews
            selectedImages = [];
            previewContainer.innerHTML = '';

            // Limit to 10 images
            if (files.length > 10) {
                alert('You can only upload up to 10 images. Only the first 10 will be processed.');
                files.splice(10);
            }

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    selectedImages.push(file);
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'image-preview relative';

                        imageDiv.innerHTML = `
                            <img src="${e.target.result}" alt="Preview ${index + 1}"
                                 class="w-full h-24 object-cover rounded-lg border">
                            <div class="remove-image" onclick="removeImage(${index})">×</div>
                            ${index === 0 ? '<div class="absolute bottom-1 left-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">Main</div>' : ''}
                        `;

                        previewContainer.appendChild(imageDiv);
                    };

                    reader.readAsDataURL(file);
                }
            });
        }

        function removeImage(index) {
            selectedImages.splice(index, 1);
            updateImagePreviews();
            updateFileInput();
        }

        function updateImagePreviews() {
            const previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';

            selectedImages.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image-preview relative';

                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}"
                             class="w-full h-24 object-cover rounded-lg border">
                        <div class="remove-image" onclick="removeImage(${index})">×</div>
                        ${index === 0 ? '<div class="absolute bottom-1 left-1 bg-blue-600 text-white text-xs px-2 py-1 rounded">Main</div>' : ''}
                    `;

                    previewContainer.appendChild(imageDiv);
                };

                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const input = document.getElementById('images');
            const dt = new DataTransfer();

            selectedImages.forEach(file => {
                dt.items.add(file);
            });

            input.files = dt.files;
        }

        // Drag and drop functionality
        const dropZone = document.querySelector('.drop-zone');

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');

            const files = Array.from(e.dataTransfer.files);
            const input = document.getElementById('images');

            // Update file input
            const dt = new DataTransfer();
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    dt.items.add(file);
                }
            });

            input.files = dt.files;
            previewImages(input);
        });
    </script>
</body>
</html>
