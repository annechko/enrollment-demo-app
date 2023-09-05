import axios from 'axios'

export function submitForm ({ event, state, setState, url, formId, onSuccess, headers }) {
  event.preventDefault()
  event.stopPropagation()
  if (state.loading) {
    return
  }

  setState({
    ...state,
    loading: true,
    error: null
  })
  axios.post(url, document.getElementById(formId), {
    headers: headers || {
      'Content-Type': 'application/json'
    }
  })
    .then(response => {
      setState({
        ...state,
        loading: false,
        error: null
      })
      if (onSuccess) {
        onSuccess(response)
      }
    })
    .catch((error) => {
      setState({
        ...state,
        loading: false,
        error: error.response?.data?.error || 'Something went wrong'
      })
    })
}
