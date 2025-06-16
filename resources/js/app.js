import './bootstrap';

// Main JavaScript file for Koperasi Management System

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
  // Initialize tooltips
  initializeTooltips()

  // Initialize modals
  initializeModals()

  // Initialize form validations
  initializeFormValidations()

  // Initialize data tables
  initializeDataTables()

  // Initialize notifications
  initializeNotifications()
})

// Tooltip initialization
function initializeTooltips() {
  const tooltipElements = document.querySelectorAll("[data-tooltip]")
  tooltipElements.forEach((element) => {
    element.addEventListener("mouseenter", showTooltip)
    element.addEventListener("mouseleave", hideTooltip)
  })
}

function showTooltip(event) {
  const tooltip = document.createElement("div")
  tooltip.className = "absolute bg-gray-800 text-white text-sm px-2 py-1 rounded shadow-lg z-50"
  tooltip.textContent = event.target.getAttribute("data-tooltip")
  tooltip.style.top = event.target.offsetTop - 35 + "px"
  tooltip.style.left = event.target.offsetLeft + "px"
  tooltip.id = "tooltip"
  document.body.appendChild(tooltip)
}

function hideTooltip() {
  const tooltip = document.getElementById("tooltip")
  if (tooltip) {
    tooltip.remove()
  }
}

// Modal functionality
function initializeModals() {
  const modalTriggers = document.querySelectorAll("[data-modal]")
  modalTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal")
      openModal(modalId)
    })
  })

  const modalCloses = document.querySelectorAll("[data-modal-close]")
  modalCloses.forEach((close) => {
    close.addEventListener("click", function () {
      const modalId = this.getAttribute("data-modal-close")
      closeModal(modalId)
    })
  })
}

function openModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.remove("hidden")
    modal.classList.add("flex")
    document.body.style.overflow = "hidden"
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.add("hidden")
    modal.classList.remove("flex")
    document.body.style.overflow = "auto"
  }
}

// Form validation
function initializeFormValidations() {
  const forms = document.querySelectorAll("form[data-validate]")
  forms.forEach((form) => {
    form.addEventListener("submit", function (event) {
      if (!validateForm(this)) {
        event.preventDefault()
      }
    })
  })
}

function validateForm(form) {
  let isValid = true
  const requiredFields = form.querySelectorAll("[required]")

  requiredFields.forEach((field) => {
    if (!field.value.trim()) {
      showFieldError(field, "Field ini wajib diisi")
      isValid = false
    } else {
      clearFieldError(field)
    }
  })

  // Email validation
  const emailFields = form.querySelectorAll('input[type="email"]')
  emailFields.forEach((field) => {
    if (field.value && !isValidEmail(field.value)) {
      showFieldError(field, "Format email tidak valid")
      isValid = false
    }
  })

  return isValid
}

function showFieldError(field, message) {
  clearFieldError(field)
  const errorDiv = document.createElement("div")
  errorDiv.className = "text-red-500 text-sm mt-1 field-error"
  errorDiv.textContent = message
  field.parentNode.appendChild(errorDiv)
  field.classList.add("border-red-500")
}

function clearFieldError(field) {
  const existingError = field.parentNode.querySelector(".field-error")
  if (existingError) {
    existingError.remove()
  }
  field.classList.remove("border-red-500")
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

// Data table functionality
function initializeDataTables() {
  const tables = document.querySelectorAll("[data-table]")
  tables.forEach((table) => {
    addTableFeatures(table)
  })
}

function addTableFeatures(table) {
  // Add sorting functionality
  const headers = table.querySelectorAll("th[data-sort]")
  headers.forEach((header) => {
    header.style.cursor = "pointer"
    header.addEventListener("click", function () {
      sortTable(table, this.getAttribute("data-sort"))
    })
  })
}

function sortTable(table, column) {
  const tbody = table.querySelector("tbody")
  const rows = Array.from(tbody.querySelectorAll("tr"))
  const columnIndex = Number.parseInt(column)

  rows.sort((a, b) => {
    const aText = a.cells[columnIndex].textContent.trim()
    const bText = b.cells[columnIndex].textContent.trim()
    return aText.localeCompare(bText)
  })

  rows.forEach((row) => tbody.appendChild(row))
}

// Notification system
function initializeNotifications() {
  // Auto-hide notifications after 5 seconds
  const notifications = document.querySelectorAll(".notification")
  notifications.forEach((notification) => {
    setTimeout(() => {
      hideNotification(notification)
    }, 5000)
  })
}

// resources/js/app.js

// ... (fungsi lain) ...

function showNotification(message, type = "info") { // Default type 'info'
  const notification = document.createElement("div");
  let bgColorClass = 'bg-blue-500'; // Default untuk info

  if (type === "error") {
    bgColorClass = 'bg-red-500';
  } else if (type === "success") {
    bgColorClass = 'bg-green-500';
  } else if (type === "warning") {
    bgColorClass = 'bg-yellow-500'; // Warna untuk warning
  }
  // Tambahkan ikon berdasarkan tipe
  let iconClass = 'fas fa-info-circle';
  if (type === "error") iconClass = 'fas fa-exclamation-circle';
  else if (type === "success") iconClass = 'fas fa-check-circle';
  else if (type === "warning") iconClass = 'fas fa-exclamation-triangle';


  // Menggunakan class yang ada di app.css Anda untuk notifikasi
  // dan menambahkan warna background dinamis
  notification.className = `notification ${bgColorClass} text-white p-4 rounded-lg shadow-lg flex items-center`;
  notification.innerHTML = `<i class="${iconClass} mr-3 text-xl"></i> <span>${message}</span>`;


  document.body.appendChild(notification);

  setTimeout(() => {
    hideNotification(notification);
  }, 5000); // Durasi notifikasi
}

function hideNotification(notification) {
    if (!notification) return; // Guard clause
    // Animasi fade out atau slide out bisa ditambahkan di sini
    notification.style.transition = 'opacity 0.3s ease, transform 0.3s ease-out';
    notification.style.opacity = "0";
    notification.style.transform = "translateX(100%)"; // Slide out ke kanan
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300); // Sesuaikan dengan durasi transisi CSS
}

// ... (sisa fungsi di app.js) ...

window.KoperasiApp = {
  showNotification,
  hideNotification,
  // ... fungsi lain ...
};

function hideNotification(notification) {
  notification.style.opacity = "0"
  notification.style.transform = "translateX(100%)"
  setTimeout(() => {
    if (notification.parentNode) {
      notification.parentNode.removeChild(notification)
    }
  }, 300)
}

// Utility functions
function formatCurrency(amount) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
  }).format(amount)
}

function formatDate(date) {
  return new Intl.DateTimeFormat("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  }).format(new Date(date))
}

function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// AJAX helper functions
function makeRequest(url, options = {}) {
  const defaultOptions = {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
    },
  }

  return fetch(url, { ...defaultOptions, ...options }).then((response) => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }
    return response.json()
  })
}

// Export functions for global use
window.KoperasiApp = {
  showNotification,
  hideNotification,
  openModal,
  closeModal,
  formatCurrency,
  formatDate,
  makeRequest,
  debounce,
}

window.KoperasiApp.confirmDelete = function(deleteUrl, itemName, itemType = 'item') {
    const modalId = 'confirmDeleteModal'; // ID untuk modal konfirmasi

    // Buat struktur HTML modal jika belum ada, atau pastikan sudah ada di layout utama
    let modalElement = document.getElementById(modalId);
    if (!modalElement) {
        modalElement = document.createElement('div');
        modalElement.id = modalId;
        // Style dasar modal (bisa disesuaikan dengan desain Anda)
        modalElement.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden items-center justify-center z-50 animate-fade-in';
        modalElement.innerHTML = `
            <div class="relative p-6 border w-full max-w-md mx-auto shadow-xl rounded-2xl bg-white animate-bounce-in">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                    </div>
                    <h3 class="text-xl leading-6 font-semibold text-gray-900">Konfirmasi Penghapusan</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-600" id="${modalId}Message">
                            Apakah Anda yakin ingin menghapus ${itemType} "${itemName}"? Tindakan ini tidak dapat diurungkan.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3 space-x-3">
                        <button id="${modalId}ConfirmButton" 
                                class="px-6 py-2.5 bg-red-600 text-white text-base font-medium rounded-xl shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                            Ya, Hapus
                        </button>
                        <button id="${modalId}CancelButton" 
                                class="px-6 py-2.5 bg-gray-200 text-gray-800 text-base font-medium rounded-xl shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalElement);
    } else {
        // Update message jika modal sudah ada
        document.getElementById(`${modalId}Message`).innerHTML = `Apakah Anda yakin ingin menghapus ${itemType} "${itemName}"? Tindakan ini tidak dapat diurungkan.`;
    }

    KoperasiApp.openModal(modalId);

    const confirmButton = document.getElementById(`${modalId}ConfirmButton`);
    const cancelButton = document.getElementById(`${modalId}CancelButton`);

    // Hapus event listener lama agar tidak menumpuk jika fungsi ini dipanggil berkali-kali
    const newConfirmButton = confirmButton.cloneNode(true);
    confirmButton.parentNode.replaceChild(newConfirmButton, confirmButton);
    
    newConfirmButton.onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = deleteUrl;
        form.style.display = 'none';
        
        const csrfTokenInput = document.createElement('input');
        csrfTokenInput.type = 'hidden';
        csrfTokenInput.name = '_token';
        csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfTokenInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
        KoperasiApp.closeModal(modalId);
    };

    cancelButton.onclick = function() {
        KoperasiApp.closeModal(modalId);
    };
}