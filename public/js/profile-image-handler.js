class ProfileImageHandler {
  constructor(options = {}) {
    this.options = {
      maxFileSize: 2 * 1024 * 1024, // 2MB
      allowedTypes: ["image/jpeg", "image/jpg", "image/png", "image/webp", "image/gif"],
      cropSize: 300,
      quality: 0.9,
      ...options,
    }

    this.cropperInstance = null
    this.originalFileDetails = null

    this.init()
  }

  init() {
    this.bindEvents()
  }

  bindEvents() {
    // File input change
    const fileInput = document.getElementById("profile_photo_original_input_for_js")
    if (fileInput) {
      fileInput.addEventListener("change", (e) => this.handleFileSelect(e))
    }

    // Modal controls
    const closeBtn = document.getElementById("closeCropModalBtn")
    const cancelBtn = document.getElementById("cancelCropModalBtn")
    const applyBtn = document.getElementById("applyCropBtn")

    if (closeBtn) closeBtn.addEventListener("click", () => this.closeCropModal())
    if (cancelBtn) cancelBtn.addEventListener("click", () => this.closeCropModal())
    if (applyBtn) applyBtn.addEventListener("click", () => this.applyCrop())

    // Delete photo
    const deleteBtn = document.getElementById("deleteProfilePhotoButton")
    if (deleteBtn) {
      deleteBtn.addEventListener("click", (e) => this.handleDeletePhoto(e))
    }
  }

  handleFileSelect(event) {
    const files = event.target.files
    if (!files || files.length === 0) return

    const file = files[0]

    // Validate file
    if (!this.validateFile(file)) {
      event.target.value = ""
      return
    }

    this.originalFileDetails = {
      name: file.name,
      type: file.type,
      size: file.size,
    }

    // Update file name display
    const fileNameDisplay = document.getElementById("fileNameDisplay")
    if (fileNameDisplay) {
      fileNameDisplay.textContent = `File dipilih: ${file.name}`
    }

    // Read and display file
    const reader = new FileReader()
    reader.onload = (e) => this.initCropper(e.target.result)
    reader.readAsDataURL(file)
  }

  validateFile(file) {
    // Check file size
    if (file.size > this.options.maxFileSize) {
      this.showNotification(
        `Ukuran file terlalu besar. Maksimal ${this.formatFileSize(this.options.maxFileSize)}.`,
        "error",
      )
      return false
    }

    // Check file type
    if (!this.options.allowedTypes.includes(file.type)) {
      this.showNotification("Tipe file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.", "error")
      return false
    }

    return true
  }

  initCropper(imageSrc) {
    const modal = document.getElementById("cropImageModal")
    const image = document.getElementById("imageToCropInModal")

    if (!modal || !image) return

    // Show modal
    modal.classList.remove("hidden")
    modal.classList.add("flex")

    // Set image source
    image.src = imageSrc

    // Destroy existing cropper
    if (this.cropperInstance) {
      this.cropperInstance.destroy()
    }

    // Initialize new cropper
    const Cropper = window.Cropper // Declare the Cropper variable
    this.cropperInstance = new Cropper(image, {
      aspectRatio: 1,
      viewMode: 1,
      dragMode: "move",
      background: false,
      preview: ".preview-circle-container",
      responsive: true,
      checkOrientation: false,
      modal: true,
      guides: true,
      center: true,
      highlight: false,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: false,
      minCropBoxWidth: 100,
      minCropBoxHeight: 100,
      ready: () => {
        this.showNotification("Sesuaikan area crop sesuai keinginan Anda.", "info")
      },
    })
  }

  applyCrop() {
    if (!this.cropperInstance || !this.originalFileDetails) {
      this.showNotification("Pilih gambar terlebih dahulu.", "warning")
      return
    }

    // Get cropped canvas
    const canvas = this.cropperInstance.getCroppedCanvas({
      width: this.options.cropSize,
      height: this.options.cropSize,
      imageSmoothingEnabled: true,
      imageSmoothingQuality: "high",
    })

    // Convert to data URL
    const croppedImageDataURL = canvas.toDataURL(this.originalFileDetails.type || "image/jpeg", this.options.quality)

    // Update hidden input
    const hiddenInput = document.getElementById("cropped_profile_photo_data")
    if (hiddenInput) {
      hiddenInput.value = croppedImageDataURL
    }

    // Update preview image
    const previewImage = document.getElementById("currentProfileImage")
    if (previewImage) {
      previewImage.src = croppedImageDataURL
    }

    this.closeCropModal()
    this.showNotification("Foto siap diupload. Jangan lupa simpan perubahan profil.", "success")
  }

  closeCropModal() {
    const modal = document.getElementById("cropImageModal")
    if (modal) {
      modal.classList.add("hidden")
      modal.classList.remove("flex")
    }

    if (this.cropperInstance) {
      this.cropperInstance.destroy()
      this.cropperInstance = null
    }

    // Reset file input
    const fileInput = document.getElementById("profile_photo_original_input_for_js")
    if (fileInput) fileInput.value = ""

    // Reset file name display
    const fileNameDisplay = document.getElementById("fileNameDisplay")
    if (fileNameDisplay) fileNameDisplay.textContent = ""

    // Reset hidden input
    const hiddenInput = document.getElementById("cropped_profile_photo_data")
    if (hiddenInput) hiddenInput.value = ""
  }

  handleDeletePhoto(event) {
    event.preventDefault()

    if (confirm("Apakah Anda yakin ingin menghapus foto profil Anda?")) {
      const deleteForm = document.getElementById("deleteActualProfilePhotoForm")
      if (deleteForm) {
        deleteForm.submit()
      }
    }
  }

  showNotification(message, type = "info") {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll(".profile-notification")
    existingNotifications.forEach((notification) => notification.remove())

    // Create notification element
    const notification = document.createElement("div")
    notification.className = `profile-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full max-w-sm`

    const bgColor =
      {
        success: "bg-green-500",
        error: "bg-red-500",
        warning: "bg-yellow-500",
        info: "bg-blue-500",
      }[type] || "bg-blue-500"

    const icon =
      {
        success: "check-circle",
        error: "exclamation-circle",
        warning: "exclamation-triangle",
        info: "info-circle",
      }[type] || "info-circle"

    notification.className += ` ${bgColor} text-white`
    notification.innerHTML = `
            <div class="flex items-start">
                <i class="fas fa-${icon} mr-3 mt-0.5 flex-shrink-0"></i>
                <span class="text-sm">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `

    document.body.appendChild(notification)

    // Animate in
    setTimeout(() => {
      notification.classList.remove("translate-x-full")
    }, 100)

    // Auto remove after 5 seconds
    setTimeout(() => {
      if (notification.parentElement) {
        notification.classList.add("translate-x-full")
        setTimeout(() => {
          if (notification.parentElement) {
            notification.remove()
          }
        }, 300)
      }
    }, 5000)
  }

  formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes"
    const k = 1024
    const sizes = ["Bytes", "KB", "MB", "GB"]
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return Number.parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
  }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  new ProfileImageHandler()
})
