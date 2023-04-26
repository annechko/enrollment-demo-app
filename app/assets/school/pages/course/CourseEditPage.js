import React from 'react'
import {
  useNavigate,
  useParams
} from "react-router-dom";
import CourseForm from "../../views/course/CourseForm";
import {submitForm} from "../helper/_submitForm";
import Loadable from "../Loadable";

const CourseEditPage = () => {
  const params = useParams()
  const [campusValue, setCampusValue] = React.useState(null)

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

  return <Loadable
    Component={CourseForm}
    url={window.abeApp.urls.api_school_course}
    config={{params: {'courseId': params.id}}}
    formId={formId}
    onSubmit={onSubmit}
    isSubmitted={state.loading}
    submitError={state.error}
    isUpdate
    setCampusValue={setCampusValue}
    campusValue={campusValue}
    customOnLoad={(data) => {
      setCampusValue(data.selectedCampuses)
    }}
  />
}

export default CourseEditPage
