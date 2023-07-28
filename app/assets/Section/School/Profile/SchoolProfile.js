import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CForm,
  CFormInput,
  CFormLabel,
  CSpinner,
  CToast,
  CToastBody,
  CToaster,
} from '@coreui/react'
import React from 'react'
import AppBackButton from "../../../App/Common/AppBackButton";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import Loadable from "../../../App/Helper/Loadable";
import {submitForm} from "../../../App/Helper/SubmitForm";

const SchoolProfileForm = ({
                             formId,
                             dataState,
                           }) => {
  const [showSuccess, setShowSuccess] = React.useState(false)
  const [submitState, setSubmitState] = React.useState({
    loading: false,
    error: null
  })
  const isSubmitted = submitState.loading
  const school = dataState?.data || null
  const error = submitState.error || dataState?.error || null
  if (school === null) {
    if (error !== null) {
      return <AppErrorMessage error={error}/>
    }
    return (
      <>
        <div>Loading...</div>
      </>
    )
  }

  const url = window.abeApp.urls.api_school_profile_edit
  const onSuccess = (response) => {
    setShowSuccess(true)
    setTimeout(() => {
      setShowSuccess(false)
    }, 1000)
  }
  const onSubmit = (event) => {
    submitForm({
      event,
      state: submitState,
      setState: setSubmitState,
      url: url,
      formId,
      onSuccess,
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }
  return <>
    <CToaster push={showSuccess === true &&
      <CToast autohide visible color="success">
        <CToastBody>School was updated!</CToastBody>
      </CToast>
    } placement="top-end"/>

    <AppErrorMessage error={error}/>
    <CForm method="post" onSubmit={onSubmit} id={formId}>
      <div className="mb-2">
        <CFormLabel className="mb-0" htmlFor="name">Name</CFormLabel>
        <CFormInput
          id="name"
          name={formId + "[name]"}
          defaultValue={school.name}
          type="text"
        />
      </div>

      <div>
        <CButton color="success"
          size="sm"
          className={isSubmitted ? 'disabled' : ''}
          disabled={isSubmitted === true}
          type="submit">
          {isSubmitted && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
          Save
        </CButton>
      </div>
    </CForm>
  </>
}
const SchoolProfile = () => {
  const formId = 'schoolProfile'

  return (
    <>
      <AppBackButton/>
      <CCard className="mb-2">
        <CCardHeader>
          <strong>
            Update your school profile
          </strong>
        </CCardHeader>
        <CCardBody>
          <Loadable
            component={SchoolProfileForm}
            url={window.abeApp.urls.api_school_profile}
            formId={formId}
          />
        </CCardBody>
      </CCard>
    </>
  )
}
export default React.memo(SchoolProfile)
