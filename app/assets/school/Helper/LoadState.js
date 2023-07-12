export function needLoading(state) {
  return !state.loaded && !state.loading && state.error === null;
}

export function error(message = null) {
  return {
    data: null,
    loading: false,
    loaded: false,
    error: message || 'Something went wrong'
  };
}

export function finishLoading(data = null) {
  return {
    data: data,
    loading: false,
    loaded: true,
    error: null
  };
}

export function startLoading() {
  return {
    data: null,
    loading: true,
    loaded: false,
    error: null
  }
}

export function initialize() {
  return {
    data: null,
    loading: false,
    loaded: false,
    error: null
  }
}
