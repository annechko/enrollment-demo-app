import React, {useState} from 'react'
import {useParams} from "react-router-dom";
import CourseForm from "../CourseForm";
import {submitForm} from "../../../Helper/SubmitForm";
import Loadable from "../../Loadable";
import {
  CToast,
  CToastBody,
  CToaster
} from "@coreui/react";

const CourseEditPage = () => {
  const params = useParams()

  const [state, setState] = useState({
    loading: false,
    error: null
  })

  const onSuccess = (response) => {
    setState({...state, showSuccess: true})
    setTimeout(() => {
      setState({
        loading: false,
        error: null
      })
    }, 1000)
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

  return <>
    <CToaster push={state?.showSuccess === true &&
      <CToast autohide visible color="success">
        <CToastBody>Course was updated!</CToastBody>
      </CToast>
    } placement="top-end"/>

    <Loadable
      component={CourseForm}
      url={window.abeApp.urls.api_school_course}
      config={{params: {'courseId': params.id}}}
      formId={formId}
      onSubmit={onSubmit}
      isSubmitted={state.loading}
      submitError={state.error}
      isUpdate
    />
  </>
}

export default CourseEditPage
