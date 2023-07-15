import React, {useState} from 'react'
import {useNavigate} from "react-router-dom";
import CourseForm from "../CourseForm";
import {submitForm} from "../../../../Helper/SubmitForm";

const CourseAddPage = () => {

  const navigate = useNavigate();
  const [state, setState] = useState({
    loading: false,
    error: null
  })

  const onSuccess = (response) => {
    navigate(window.abeApp.urls.school_course_edit.replace(':id', response.data.id))
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

  return <CourseForm
    formId={formId}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
  />
}

export default CourseAddPage
