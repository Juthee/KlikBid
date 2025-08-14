<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Auction - KlikBid</title>
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
        .main-image-badge {
            position: absolute;
            bottom: 4px;
            left: 4px;
            background: #3b82f6;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 4px;
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
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center">
                        <h1 class="text-3xl font-bold text-blue-600">KlikBid</h1>
                        <span class="ml-2 text-sm text-gray-500">Sri Lanka</span>
                    </a>
                </div>
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
            <h2 class="text-3xl font-bold text-gray-900">Edit Your Auction</h2>
            <p class="mt-2 text-gray-600">Update your auction details before approval</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
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

        <!-- Edit Form -->
        <form method="POST" action="{{ route('auctions.update', $auction) }}" enctype="multipart/form-data" class="bg-white shadow-lg rounded-lg p-8">
            @csrf
            @method('PUT')

            <!-- Image Management Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">📸 Auction Images</h3>

                <!-- Existing Images -->
                @if($auction->images && count($auction->images) > 0)
                    <div class="mb-4">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">Current Images</h4>
                        <div id="existingImages" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
                            @foreach($auction->images as $index => $image)
                                <div class="existing-image-preview relative" data-image="{{ $image }}">
                                    <img src="{{ asset('storage/' . $image) }}"
                                         alt="Current image {{ $index + 1 }}"
                                         class="w-full h-24 object-cover rounded-lg border">
                                    <div class="remove-image" onclick="removeExistingImage('{{ $image }}', this)">×</div>
                                    @if($index === 0)
                                        <div class="main-image-badge">Main</div>
                                    @endif
                                    <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Add New Images -->
                <div class="mb-4">
                    <h4 class="text-lg font-medium text-gray-700 mb-3">Add New Images</h4>
                    <div class="drop-zone bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors duration-200" onclick="document.getElementById('images').click()">
                        <div class="space-y-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="text-lg font-medium text-gray-900">Click to add more images</div>
                            <div class="text-sm text-gray-500">or drag and drop</div>
                        </div>
                    </div>
                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden" onchange="previewNewImages(this)">
                    <div id="newImagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4"></div>
                </div>

                <!-- Image Guidelines -->
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h4 class="font-medium text-blue-900 mb-2">📋 Image Management Tips</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• First image will be your main display image</li>
                        <li>• Click the × button to remove unwanted images</li>
                        <li>• You can add up to 10 images total</li>
                        <li>• At least one image is required</li>
                    </ul>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Basic Information</h3>

                <!-- Title -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Auction Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $auction->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
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
                                    <option value="{{ $subcategory->id }}"
                                            {{ old('category_id', $auction->category_id) == $subcategory->id ? 'selected' : '' }}>
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
                              required>{{ old('description', $auction->description) }}</textarea>
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
                            <input type="number" id="base_price" name="base_price"
                                   value="{{ old('base_price', $auction->base_price / 100) }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   required>
                        </div>
                    </div>

                    <!-- Reserve Price -->
                    <div>
                        <label for="reserve_price" class="block text-sm font-medium text-gray-700 mb-2">Reserve Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rs</span>
                            <input type="number" id="reserve_price" name="reserve_price"
                                   value="{{ old('reserve_price', $auction->reserve_price ? $auction->reserve_price / 100 : '') }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Buy Now Price -->
                    <div>
                        <label for="buy_now_price" class="block text-sm font-medium text-gray-700 mb-2">Buy Now Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">Rs</span>
                            <input type="number" id="buy_now_price" name="buy_now_price"
                                   value="{{ old('buy_now_price', $auction->buy_now_price ? $auction->buy_now_price / 100 : '') }}"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Location Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address_line" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <input type="text" id="address_line" name="address_line"
                               value="{{ old('address_line', $auction->address_line) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- District -->
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                        <select id="district" name="district"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select District</option>
                            <option value="Colombo" {{ old('district', $auction->district) == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                            <option value="Kandy" {{ old('district', $auction->district) == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                            <option value="Gampaha" {{ old('district', $auction->district) == 'Gampaha' ? 'selected' : '' }}>Gampaha</option>
                            <option value="Kalutara" {{ old('district', $auction->district) == 'Kalutara' ? 'selected' : '' }}>Kalutara</option>
                            <option value="Galle" {{ old('district', $auction->district) == 'Galle' ? 'selected' : '' }}>Galle</option>
                            <option value="Matara" {{ old('district', $auction->district) == 'Matara' ? 'selected' : '' }}>Matara</option>
                            <!-- Add other districts as needed -->
                        </select>
                    </div>
                </div>

                <!-- Province -->
                <div class="mt-4">
                    <label for="province" class="block text-sm font-medium text-gray-700 mb-2">Province</label>
                    <select id="province" name="province"
                            class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Province</option>
                        <option value="Western" {{ old('province', $auction->province) == 'Western' ? 'selected' : '' }}>Western</option>
                        <option value="Central" {{ old('province', $auction->province) == 'Central' ? 'selected' : '' }}>Central</option>
                        <option value="Southern" {{ old('province', $auction->province) == 'Southern' ? 'selected' : '' }}>Southern</option>
                        <!-- Add other provinces as needed -->
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
                        <input type="datetime-local" id="start_at" name="start_at"
                               value="{{ old('start_at', $auction->start_at->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>

                    <!-- End Date/Time -->
                    <div>
                        <label for="end_at" class="block text-sm font-medium text-gray-700 mb-2">Auction End *</label>
                        <input type="datetime-local" id="end_at" name="end_at"
                               value="{{ old('end_at', $auction->end_at->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    💾 Update Auction
                </button>
                <a href="{{ route('auctions.show', $auction) }}"
                   class="flex-1 bg-gray-300 text-gray-700 py-3 px-6 rounded-lg font-semibold text-center hover:bg-gray-400 transition duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        let newSelectedImages = [];

        function removeExistingImage(imagePath, element) {
            // Remove from DOM
            element.parentElement.remove();
        }

        function previewNewImages(input) {
            const files = Array.from(input.files);
            const previewContainer = document.getElementById('newImagePreview');

            // Clear existing previews
            newSelectedImages = [];
            previewContainer.innerHTML = '';

            files.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    newSelectedImages.push(file);
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'image-preview relative';

                        imageDiv.innerHTML = `
                            <img src="${e.target.result}" alt="New preview ${index + 1}"
                                 class="w-full h-24 object-cover rounded-lg border">
                            <div class="remove-image" onclick="removeNewImage(${index})">×</div>
                        `;

                        previewContainer.appendChild(imageDiv);
                    };

                    reader.readAsDataURL(file);
                }
            });
        }

        function removeNewImage(index) {
            newSelectedImages.splice(index, 1);
            updateNewImagePreviews();
            updateFileInput();
        }

        function updateNewImagePreviews() {
            const previewContainer = document.getElementById('newImagePreview');
            previewContainer.innerHTML = '';

            newSelectedImages.forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imageDiv = document.createElement('div');
                    imageDiv.className = 'image-preview relative';

                    imageDiv.innerHTML = `
                        <img src="${e.target.result}" alt="New preview ${index + 1}"
                             class="w-full h-24 object-cover rounded-lg border">
                        <div class="remove-image" onclick="removeNewImage(${index})">×</div>
                    `;

                    previewContainer.appendChild(imageDiv);
                };

                reader.readAsDataURL(file);
            });
        }

        function updateFileInput() {
            const input = document.getElementById('images');
            const dt = new DataTransfer();

            newSelectedImages.forEach(file => {
                dt.items.add(file);
            });

            input.files = dt.files;
        }
    </script>
</body>
</html>
