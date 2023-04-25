import React, {useState} from 'react'
import axios from "axios";
import PropTypes from "prop-types";

const LoadablePage = ({Component, url, ...options}) => {
  const [dataState, setDataState] = useState({
    data: null,
    loading: false,
    loaded: false,
    error: null
  })

  const onLoad = (response) => {
    setDataState({
      data: response.data,
      loading: false,
      loaded: true,
      error: null
    })
  }
  const onError = (error) => {
    setDataState({
      data: null,
      loading: false,
      loaded: false,
      error: error.response?.data?.error || 'Something went wrong'
    })
  }
  const loadData = () => {
    setDataState({
      data: null,
      loading: true,
      loaded: false,
      error: null
    })
    axios.get(url)
      .then(onLoad)
      .catch(onError)
  }
  React.useEffect(() => {
    if (!dataState.loaded && !dataState.loading && dataState.error === null) {
      loadData()
    }
  }, [dataState.loaded, dataState.loading, dataState.error])

  return <Component dataState={dataState} {...options}/>
}
LoadablePage.propTypes = {
  Component: PropTypes.elementType.isRequired,
  url: PropTypes.string.isRequired,
}
export default LoadablePage
