import React, {useState} from 'react'
import axios from "axios";
import {useNavigate, useParams} from "react-router-dom";
import {submitForm} from "../helper/_submitForm";
import CampusForm from "../../views/campus/CampusForm";

const CampusEditPage = () => {
  const params = useParams()
  const [itemState, setItemState] = useState({
    item: null,
    loading: false,
    loaded: false,
    error: null
  })

  const onLoad = (response) => {
    setItemState({
      item: response.data,
      loading: false,
      loaded: true,
      error: null
    })
  }
  const onError = (error) => {
    setItemState({
      item: null,
      loading: false,
      loaded: false,
      error: error.response?.data?.error || 'Something went wrong'
    })
  }
  const loadItem = () => {
    setItemState({
      item: null,
      loading: true,
      loaded: false,
      error: null
    })
    const urls = window.abeApp.urls

    axios.get(urls.api_school_campus.replace(':id', params.id))
      .then(onLoad)
      .catch(onError)
  }
  React.useEffect(() => {
    if (!itemState.loaded && !itemState.loading && itemState.error === null) {
      loadItem()
    }
  }, [itemState.loaded, itemState.loading, itemState.error, params.id])
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = React.useState(initialState)

  const navigate = useNavigate();
  const onSuccess = (response) => {navigate(-1)}
  const formId = 'campus'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      onSuccess,
      url: window.abeApp.urls.api_school_campus_edit.replace(':id', params.id),
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }
  return <CampusForm formId={formId} onSubmit={onSubmit}
    item={itemState.item}
    isLoading={itemState.loading}
    isUpdate/>
}

export default CampusEditPage
