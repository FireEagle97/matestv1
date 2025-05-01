// Form validation for movie upload
document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form')
  const videoInput = document.getElementById('video')
  const audioInput = document.getElementById('audio')
  const subtitlesInput = document.getElementById('subtitles')
  const documentsInput = document.getElementById('documents')
  const priceInput = document.getElementById('price')

  // File size validation (in bytes)
  const MAX_VIDEO_SIZE = 10 * 1024 * 1024 * 1024 // 10GB
  const MAX_AUDIO_SIZE = 100 * 1024 * 1024 // 100MB
  const MAX_SUBTITLE_SIZE = 1 * 1024 * 1024 // 1MB
  const MAX_DOCUMENT_SIZE = 10 * 1024 * 1024 // 10MB

  // Add validation classes
  function addValidationClasses(element, isValid) {
    if (isValid) {
      element.classList.remove('border-red-300', 'text-red-900', 'placeholder-red-300', 'focus:ring-red-500', 'focus:border-red-500')
      element.classList.add('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500')
    } else {
      element.classList.remove('border-gray-300', 'focus:ring-indigo-500', 'focus:border-indigo-500')
      element.classList.add('border-red-300', 'text-red-900', 'placeholder-red-300', 'focus:ring-red-500', 'focus:border-red-500')
    }
  }

  // Show error message
  function showError(element, message) {
    let errorElement = element.nextElementSibling
    if (!errorElement || !errorElement.classList.contains('text-red-600')) {
      errorElement = document.createElement('p')
      errorElement.className = 'mt-2 text-sm text-red-600'
      element.parentNode.insertBefore(errorElement, element.nextSibling)
    }
    errorElement.textContent = message
  }

  // Remove error message
  function removeError(element) {
    const errorElement = element.nextElementSibling
    if (errorElement && errorElement.classList.contains('text-red-600')) {
      errorElement.remove()
    }
  }

  // Validate video file
  videoInput.addEventListener('change', function () {
    const file = this.files[0]
    if (file) {
      if (file.size > MAX_VIDEO_SIZE) {
        showError(this, 'Video file size must be less than 10GB')
        addValidationClasses(this, false)
      } else if (!['video/mp4', 'video/quicktime', 'video/x-msvideo'].includes(file.type)) {
        showError(this, 'Please upload a valid video file (MP4, MOV, or AVI)')
        addValidationClasses(this, false)
      } else {
        removeError(this)
        addValidationClasses(this, true)
      }
    }
  })

  // Validate audio files
  audioInput.addEventListener('change', function () {
    const files = this.files
    let isValid = true

    Array.from(files).forEach((file) => {
      if (file.size > MAX_AUDIO_SIZE) {
        showError(this, 'Audio file size must be less than 100MB each')
        isValid = false
      } else if (!['audio/mpeg', 'audio/wav', 'audio/aac'].includes(file.type)) {
        showError(this, 'Please upload valid audio files (MP3, WAV, or AAC)')
        isValid = false
      }
    })

    if (isValid) {
      removeError(this)
      addValidationClasses(this, true)
    } else {
      addValidationClasses(this, false)
    }
  })

  // Validate subtitle files
  subtitlesInput.addEventListener('change', function () {
    const files = this.files
    let isValid = true

    Array.from(files).forEach((file) => {
      if (file.size > MAX_SUBTITLE_SIZE) {
        showError(this, 'Subtitle file size must be less than 1MB each')
        isValid = false
      } else if (!['.srt', '.vtt'].includes(file.name.slice(-4).toLowerCase())) {
        showError(this, 'Please upload valid subtitle files (SRT or VTT)')
        isValid = false
      }
    })

    if (isValid) {
      removeError(this)
      addValidationClasses(this, true)
    } else {
      addValidationClasses(this, false)
    }
  })

  // Validate document files
  documentsInput.addEventListener('change', function () {
    const files = this.files
    let isValid = true

    Array.from(files).forEach((file) => {
      if (file.size > MAX_DOCUMENT_SIZE) {
        showError(this, 'Document file size must be less than 10MB each')
        isValid = false
      } else if (!['.pdf', '.doc', '.docx'].includes(file.name.slice(-4).toLowerCase())) {
        showError(this, 'Please upload valid document files (PDF, DOC, or DOCX)')
        isValid = false
      }
    })

    if (isValid) {
      removeError(this)
      addValidationClasses(this, true)
    } else {
      addValidationClasses(this, false)
    }
  })

  // Validate price
  priceInput.addEventListener('input', function () {
    const price = parseFloat(this.value)
    if (isNaN(price) || price < 0) {
      showError(this, 'Please enter a valid price (must be 0 or greater)')
      addValidationClasses(this, false)
    } else {
      removeError(this)
      addValidationClasses(this, true)
    }
  })

  // Form submission validation
  form.addEventListener('submit', function (event) {
    let isValid = true

    // Check required fields
    const requiredFields = form.querySelectorAll('[required]')
    requiredFields.forEach((field) => {
      if (!field.value.trim()) {
        showError(field, 'This field is required')
        addValidationClasses(field, false)
        isValid = false
      }
    })

    // Check file inputs
    if (!videoInput.files.length) {
      showError(videoInput, 'Please select a video file')
      addValidationClasses(videoInput, false)
      isValid = false
    }

    if (!documentsInput.files.length) {
      showError(documentsInput, 'Please upload at least one ownership document')
      addValidationClasses(documentsInput, false)
      isValid = false
    }

    if (!isValid) {
      event.preventDefault()
    }
  })
})
