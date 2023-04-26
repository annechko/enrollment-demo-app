import React, {useState} from 'react'
import {useNavigate} from "react-router-dom";
import CampusForm from "../../views/campus/CampusForm";
import {submitForm} from "../helper/_submitForm";

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

  return <CampusForm formId={formId} onSubmit={onSubmit}
    submitError={state.error}
    isSubmitted={state.loading}
  />
}

export default CampusAddPage
