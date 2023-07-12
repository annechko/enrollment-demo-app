import React, {useState} from 'react'
import {useNavigate} from "react-router-dom";
import CampusView from "../CampusView";
import {submitForm} from "../../../Helper/SubmitForm";

const CampusAddPage = () => {

  const navigate = useNavigate();
  const [state, setState] = useState({
    loading: false,
    error: null
  })

  const onSuccess = (response) => {
    navigate(-1)
  }
  const formId = 'campus'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      onSuccess,
      url: window.abeApp.urls.api_school_campus_add,
      headers: {'Content-Type': 'multipart/form-data'}//todo should be json
    })
  }
  return <CampusView formId={formId} onSubmit={onSubmit}
    submitError={state.error}
    isSubmitted={state.loading}
  />
}

export default CampusAddPage
