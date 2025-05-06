const togglePassword = document.querySelector('#togglePassword')
const password = document.querySelector('#password')

if (togglePassword) {
  togglePassword.addEventListener('click', function () {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password'
    password.setAttribute('type', type)
    this.classList.toggle('fa-eye-slash')
  })
}

const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword')
const password_confirmation = document.querySelector('#password_confirmation')
if (toggleConfirmPassword && password_confirmation) {
  toggleConfirmPassword.addEventListener('click', function () {
    const type_confirm = password_confirmation.getAttribute('type') === 'password' ? 'text' : 'password'
    password_confirmation.setAttribute('type', type_confirm)
    this.classList.toggle('fa-eye-slash')
  })
}

const registerForm = document.querySelector('#registerForm')
const registerButton = document.querySelector('#register-button')
const errorMessage = document.querySelector('#error_message')

const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content')

if (registerForm) {
  registerForm.addEventListener('submit', async function (e) {
    e.preventDefault()
    const isValid = validateRegisterForm()
    if (!isValid) return

    toggleRegisterButton(true, registerButton)
    errorMessage.textContent = ''

    try {
      const formData = new FormData(this)

      // Get the form's action URL
      const formAction = this.getAttribute('action')

      // Use the form's action URL if it exists, otherwise fallback to default
      const apiUrl = formAction || `${baseUrl}/api/register`

      const response = await fetch(`${apiUrl}?is_ajax=1`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })

      let data
      try {
        data = await response.json()
        console.log('Registration response:', data)

        if (!response.ok) {
          if (data.errors) {
            const errorMessages = Object.values(data.errors).flat()
            errorMessage.textContent = errorMessages[0]
          } else if (data.message) {
            errorMessage.textContent = data.message
          } else {
            errorMessage.textContent = 'An error occurred during registration'
          }
          return
        }

        if (data.status === true) {
          window.location.href = data.redirect || baseUrl
        } else {
          errorMessage.textContent = data.message || 'Registration failed'
        }
      } catch (e) {
        console.error('JSON parsing error:', e)
        errorMessage.textContent = 'Server response was not in the correct format'
      }
    } catch (error) {
      console.error('Registration error:', error)
      errorMessage.textContent = 'A system error occurred. Please try again later.'
    } finally {
      toggleRegisterButton(false, registerButton)
    }
  })
}

function validateRegisterForm() {
  let isValid = true
  const firstName = registerForm.querySelector('input[name="first_name"]')
  const lastName = registerForm.querySelector('input[name="last_name"]')
  const email = registerForm.querySelector('input[name="email"]')
  const password = registerForm.querySelector('input[name="password"]')
  const password_confirmation = registerForm.querySelector('input[name="password_confirmation"]')

  // Clear previous error messages
  clearValidationError(firstName)
  clearValidationError(lastName)
  clearValidationError(email)
  clearValidationError(password)
  clearValidationError(password_confirmation)

  if (!firstName.value.trim()) {
    showValidationError(firstName, 'First Name field is required.')
    isValid = false
  }

  if (!lastName.value.trim()) {
    showValidationError(lastName, 'Last Name field is required.')
    isValid = false
  }

  if (!email.value.trim()) {
    showValidationError(email, 'Email field is required.')
    isValid = false
  } else if (!validateEmail(email.value)) {
    showValidationError(email, 'Please enter a valid email address.')
    isValid = false
  }

  if (!password.value) {
    showValidationError(password, 'Password field is required.')
    isValid = false
  } else if (password.value.length < 8) {
    showValidationError(password, 'Password must be at least 8 characters.')
    isValid = false
  }

  if (!password_confirmation.value) {
    showValidationError(password_confirmation, 'Please confirm your password.')
    isValid = false
  } else if (password.value !== password_confirmation.value) {
    showValidationError(password_confirmation, 'Passwords do not match.')
    isValid = false
  }

  return isValid
}

function showValidationError(input, message) {
  if (!input) return
  const container = input.closest('.input-group')
  if (!container) return
  const errorFeedback = container.querySelector('.invalid-feedback')
  if (errorFeedback) {
    errorFeedback.textContent = message
    input.classList.add('is-invalid')
  }
}

function clearValidationError(input) {
  if (!input) return
  const container = input.closest('.input-group')
  if (!container) return
  const errorFeedback = container.querySelector('.invalid-feedback')
  if (errorFeedback) {
    errorFeedback.textContent = ''
    input.classList.remove('is-invalid')
  }
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

function toggleRegisterButton(isSubmitting, button) {
  const registrationText = button.getAttribute('data-signup-text') || 'Sign Up'
  button.textContent = isSubmitting ? 'Signing up...' : registrationText
  button.disabled = isSubmitting
}

const loginForm = document.querySelector('#login-form')

if (loginForm) {
  const loginButton = document.querySelector('#login-button')
  const loginError = document.querySelector('#login_error_message')

  loginForm.addEventListener('submit', async function (e) {
    e.preventDefault()
    const isValid = validateloginForm()
    if (!isValid) {
      return
    }
    toggleLoginButton(true, loginButton)
    loginError.textContent = ''

    try {
      const formData = new FormData(this)
      const response = await fetch(`${baseUrl}/api/login?is_ajax=1`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })

      const data = await response.json()

      if (!response.ok) {
        handleValidationErrors(data.errors)
      }

      if (data.status === true) {
        // Check for device limit
        if (data.device_limit_reached) {
          loginError.textContent = 'Your device limit has been reached.'
          return
        }
        window.location.href = `${baseUrl}`
      } else {
        loginError.textContent = data.message
      }
    } catch (error) {
      if (error.message !== 'Validation Error') {
        loginError.textContent = 'Your device limit has been reached.'
      }
    } finally {
      toggleLoginButton(false, loginButton)
    }
  })
}

function validateloginForm() {
  let isValid = true
  const emailField = loginForm.querySelector('input[name="email"]')
  const passwordField = loginForm.querySelector('input[name="password"]')

  if (emailField && emailField.required) {
    if (emailField.value.trim() === '') {
      showValidationError(emailField, 'Email field is required.')
      isValid = false
    } else if (!validateEmail(emailField.value)) {
      showValidationError(emailField, 'Enter a valid Email Address.')
      isValid = false
    } else {
      clearValidationError(emailField)
    }
  }

  if (passwordField && passwordField.value.trim() === '') {
    passwordField.classList.add('is-invalid')
    isValid = false
  } else {
    passwordField.classList.remove('is-invalid')
  }

  return isValid
}

function toggleLoginButton(isSubmitting, button) {
  const loginText = button.getAttribute('data-login-text') || 'Sign In'
  button.textContent = isSubmitting ? 'Sign In...' : loginText
  button.disabled = isSubmitting
}

const ForgetpasswordForm = document.querySelector('#forgetpassword-form')

if (ForgetpasswordForm) {
  const forgetpasswordButton = document.querySelector('#forget_password_btn')
  const ForgetpasswordError = document.querySelector('#forgetpassword_error_message')
  const Forgetpasswordmessage = document.querySelector('#forget_password_msg')

  ForgetpasswordForm.addEventListener('submit', async function (e) {
    e.preventDefault()
    const isValid = validateforgetpasswordForm() // Manually validate the form
    if (!isValid) {
      return
    }
    toggleButton(true, forgetpasswordButton, 'Sending...')
    ForgetpasswordError.textContent = ''

    try {
      const formData = new FormData(this)
      const response = await fetch(`${baseUrl}/api/forgot-password?is_ajax=1`, {
        method: 'post',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
      })

      const data = await response.json()

      if (data.status == true) {
        Forgetpasswordmessage.classList.remove('d-none')
      } else {
        ForgetpasswordError.textContent = data.message
        toggleButton(false, forgetpasswordButton, 'Submit')
      }
    } catch (error) {
      if (error.message !== 'Validation Error') {
      }
    } finally {
      toggleButton(false, forgetpasswordButton, 'Submit')
    }
  })
}

function validateforgetpasswordForm() {
  let isValid = true
  const emailField = ForgetpasswordForm.querySelector('input[name="email"]')

  if (emailField && emailField.required) {
    if (emailField.value.trim() === '') {
      showValidationError(emailField, 'Email field is required.')
      isValid = false
    } else if (!validateEmail(emailField.value)) {
      showValidationError(emailField, 'Enter a valid Email Address.')
      isValid = false
    } else {
      clearValidationError(emailField)
    }
  }

  return isValid
}

function toggleButton(isSubmitting, button, btntext = 'Loding...') {
  const Text = button.getAttribute('data-login-text') || 'Submit'
  button.textContent = isSubmitting ? btntext : Text
  button.disabled = isSubmitting
}
