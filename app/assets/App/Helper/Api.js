import * as LoadState from './LoadState'
import axios from 'axios'

export function submitData ({ state, setState, url, data, onSuccess, headers }) {
  if (state.loading) {
    return
  }

  setState({
    ...state,
    loading: true,
    error: null
  })
  axios.post(url, data, {
    headers: headers || {
      'Content-Type': 'application/json'
    }
  })
    .then(response => {
      setState({
        ...state,
        loaded: true,
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
        loaded: true,
        loading: false,
        error: error.response?.data?.error || 'Something went wrong'
      })
    })
}

export function loadData (url, setState) {
  return () => {
    setState(LoadState.startLoading())
    axios.get(url)
      .then((response) => {
        setState(LoadState.finishLoading(response.data))
      })
      .catch((error) => {
        setState(LoadState.error(error.response?.data?.error))
      })
  }
}
