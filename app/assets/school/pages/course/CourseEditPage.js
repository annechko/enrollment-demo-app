import React from 'react'
import {useNavigate, useParams} from "react-router-dom";
import {submitForm} from "../helper/_submitForm";
import CourseForm from "../../views/course/CourseForm";
import LoadablePage from "../LoadablePage";

const CourseEditPage = () => {
  const params = useParams()
  const [state, setState] = React.useState({
    loading: false,
    error: null
  })

  const navigate = useNavigate();
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
      url: window.abeApp.urls.api_school_course_edit.replace(':id', params.id),
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }

  return <LoadablePage
    Component={CourseForm}
    url={window.abeApp.urls.api_school_course.replace(':id', params.id)}
    formId={formId}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
    isUpdate
  />
}

export default CourseEditPage
