import axios from "axios";
import PropTypes from "prop-types";
import React, {
  useEffect,
  useState
} from 'react'
import * as LoadState from "./LoadState";

const Loadable = ({component, url, customOnLoad, config, ...options}) => {
  const [dataState, setDataState] = useState(LoadState.initialize())

  useEffect(() => {
    if (LoadState.needLoading(dataState)) {
      loadData()
    }
  }, [dataState, component])

  const reload = () => {
    loadData()
  }
  const onSuccess = (response) => {
    if (customOnLoad) {
      customOnLoad(response.data)
    }
    setDataState(LoadState.finishLoading(response.data))
  }
  const onError = (error) => {
    setDataState(LoadState.error(error.response?.data?.error))
  }
  const loadData = () => {
    setDataState(LoadState.startLoading())
    axios.get(url, config || {})
      .then(onSuccess)
      .catch(onError)
  }
  const ComponentToRender = component

  return <ComponentToRender dataState={dataState} reload={reload} {...options}/>
}
Loadable.propTypes = {
  component: PropTypes.elementType.isRequired,
  url: PropTypes.string.isRequired,
  config: PropTypes.object,
  customOnLoad: PropTypes.func,
}
export default React.memo(Loadable)
