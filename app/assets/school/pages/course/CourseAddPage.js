import React, {useState} from 'react'
import {useNavigate} from "react-router-dom";
import CourseForm from "../../views/course/CourseForm";
import {submitForm} from "../helper/_submitForm";
import Loadable from "../Loadable";

const CourseAddPage = () => {

  const navigate = useNavigate();
  const [state, setState] = useState({
    loading: false,
    error: null
  })
  const [campusValue, setCampusValue] = React.useState(null)

  const onSuccess = (response) => {
    navigate(-1)
  }
  const formId = 'course'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      onSuccess,
      url: window.abeApp.urls.api_school_course_add,
      headers: {'Content-Type': 'multipart/form-data'}//todo should be json
    })
  }

  const [campusAddState, setCampusAddState] = useState({
    loading: false,
    error: null,
    success: false,
  })

  const onCampusAddSuccess = (response) => {
    setCampusAddState({
      loading: false,
      error: null,
      success: true,
    })
  }

  const onCampusAdd = (event) => {
    submitForm({
      event,
      state: campusAddState,
      setState: setCampusAddState,
      onSuccess: onCampusAddSuccess,
      formId: 'campus',
      url: window.abeApp.urls.api_school_campus_add,
      headers: {'Content-Type': 'multipart/form-data'}//todo should be json
    })
  }

  return <Loadable
    Component={CourseForm}
    url={window.abeApp.urls.api_school_course}
    formId={formId}
    onSubmit={onSubmit}
    campusAddState={campusAddState}
    setCampusAddState={setCampusAddState}
    onCampusAdd={onCampusAdd}
    isSubmitted={state.loading}
    submitError={state.error}
    setCampusValue={setCampusValue}
    campusValue={campusValue}
  />
}

export default CourseAddPage
