import React, {useState} from 'react'
import {useNavigate} from "react-router-dom";
import {submitForm} from "../helper/_submitForm";
import CampusForm from "../../views/campus/CampusForm";

const CampusAddPage = () => {

  const navigate = useNavigate();
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = useState(initialState)

  const onSuccess = (response) => {navigate(-1)}
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

  return <CampusForm formId={formId} onSubmit={onSubmit}/>
}

export default CampusAddPage
