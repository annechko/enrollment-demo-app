import React, {useEffect, useState} from 'react'
import axios from "axios";
import CampusList from "./../../views/campus/CampusList";

const CampusListPage = () => {
  const [itemsState, setItemsState] = useState({
    items: null,
    loading: false,
    loaded: false,
    error: null
  })
  const onLoad = (response) => {
    setItemsState({
      items: response.data,
      loading: false,
      loaded: true,
      error: null
    })
  }
  const onError = (error) => {
    setItemsState({
      items: null,
      loading: false,
      loaded: false,
      error: error.response?.data?.error || 'Something went wrong'
    })
  }
  const loadNavItems = () => {
    setItemsState({
      items: null,
      loading: true,
      loaded: false,
      error: null
    })
    const urls = window.abeApp.urls

    axios.get(urls.api_school_campus_list)
      .then(onLoad)
      .catch(onError)
  }
  useEffect(() => {
    if (!itemsState.loaded && !itemsState.loading && itemsState.error === null) {
      loadNavItems()
    }
  }, [itemsState.loaded, itemsState.loading, itemsState.error])

  return <CampusList items={itemsState.items}/>
}

export default CampusListPage
