import React, {useState} from 'react'
import {
  useNavigate,
  useParams
} from "react-router-dom";
import CourseForm from "../../views/course/CourseForm";
import {submitForm} from "../helper/_submitForm";
import Loadable from "../Loadable";

const CourseEditPage = () => {
  const navigate = useNavigate();
  const params = useParams()

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
      url: window.abeApp.urls.api_school_course_edit.replace(':id', params.id),
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }

  return <Loadable
    Component={CourseForm}
    url={window.abeApp.urls.api_school_course}
    config={{params: {'courseId': params.id}}}
    formId={formId}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
    isUpdate
  />
}

export default CourseEditPage
