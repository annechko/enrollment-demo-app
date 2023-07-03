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

  return <Loadable
    Component={CourseForm}
    url={window.abeApp.urls.api_school_course}
    formId={formId}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
  />
}

export default CourseAddPage
