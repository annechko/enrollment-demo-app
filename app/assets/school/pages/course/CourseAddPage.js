import React, {useState} from 'react'
import {useNavigate} from "react-router-dom";
import {submitForm} from "../helper/_submitForm";
import CourseForm from "../../views/course/CourseForm";

const CourseAddPage = () => {

  const navigate = useNavigate();
  const [state, setState] = useState({
    loading: false,
    error: null
  })

  const onSuccess = (response) => {navigate(-1)}
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

  return <CourseForm formId={formId} onSubmit={onSubmit}
    submitError={state.error}
    isSubmitted={state.loading}
  />
}

export default CourseAddPage
