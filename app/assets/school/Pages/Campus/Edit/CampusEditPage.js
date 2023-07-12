import React from 'react'
import {
  useNavigate,
  useParams
} from "react-router-dom";
import CampusView from "../CampusView";
import {submitForm} from "../../../Helper/SubmitForm";
import Loadable from "../../Loadable";

const CampusEditPage = () => {
  const params = useParams()
  const [state, setState] = React.useState({
    loading: false,
    error: null
  })

  const navigate = useNavigate();
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
      url: window.abeApp.urls.api_school_campus_edit.replace(':id', params.id),
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }

  return <Loadable
    component={CampusView}
    url={window.abeApp.urls.api_school_campus.replace(':id', params.id)}
    formId={formId}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
    isUpdate
  />
}

export default CampusEditPage
