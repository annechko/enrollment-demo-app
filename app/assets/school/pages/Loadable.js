import axios from "axios";
import PropTypes from "prop-types";
import React, {useState} from 'react'

const Loadable = ({Component, url, customOnLoad, config, ...options}) => {
  const [dataState, setDataState] = useState({
    data: null,
    loading: false,
    loaded: false,
    error: null
  })

  const reload = () => {
    loadData()
  }
  const onLoad = (response) => {
    if (customOnLoad) {
      customOnLoad(response.data)
    }
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
    axios.get(url, config || {})
      .then(onLoad)
      .catch(onError)
  }
  React.useEffect(() => {
    if (!dataState.loaded && !dataState.loading && dataState.error === null) {
      loadData()
    }
  }, [dataState.loaded, dataState.loading, dataState.error, Component])

  return <Component dataState={dataState} reload={reload} {...options}/>
}
Loadable.propTypes = {
  Component: PropTypes.elementType.isRequired,
  url: PropTypes.string.isRequired,
  config: PropTypes.object,
  customOnLoad: PropTypes.func,
}
export default React.memo(Loadable)
