export function needLoading(state: any): boolean {
  return !state.loaded && !state.loading && state.error === null;
}

export function error(message: string | null = null): object {
  return {
    data: null,
    loading: false,
    loaded: false,
    error: message || 'Something went wrong'
  };
}

export function finishLoading(data: object | null = null): object {
  return {
    data: data,
    loading: false,
    loaded: true,
    error: null
  };
}

export function startLoading(): object {
  return {
    data: null,
    loading: true,
    loaded: false,
    error: null
  }
}

export function initialize(): object {
  return {
    data: null,
    loading: false,
    loaded: false,
    error: null
  }
}
