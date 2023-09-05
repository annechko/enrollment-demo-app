import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CForm,
  CFormInput,
  CFormLabel,
  CToast,
  CToastBody,
  CToaster
} from '@coreui/react'
import React, { useRef } from 'react'
import AppBackButton from '../../../App/Common/AppBackButton'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import Loadable from '../../../App/Helper/Loadable'
import AppDataLoader from '../../../App/Common/AppDataLoader'
import { submitData } from '../../../App/Helper/Api'

const SchoolProfileForm = ({
  dataState
}) => {
  const nameRef = useRef(null)
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
  const onSubmit = (e) => {
    e.preventDefault()
    submitData({
      state: submitState,
      setState: setSubmitState,
      data: { name: nameRef.current.value },
      url,
      onSuccess,
    })
  }
  return <>
    <CToaster
      push={showSuccess &&
        <CToast autohide visible color="success" data-testid="success-msg">
          <CToastBody>School was updated!</CToastBody>
        </CToast>
      }
      placement="top-end"/>

    <AppErrorMessage error={error}/>
    <CForm>
      <div className="mb-2">
        <CFormLabel className="mb-0" htmlFor="name">Name</CFormLabel>
        <CFormInput
          ref={nameRef}
          data-testid="profile-name"
          id="name"
          defaultValue={school.name}
          type="text"
        />
      </div>

      <div>
        <CButton color="success"
          onClick={onSubmit}
          data-testid="btn-submit"
          size="sm"
          className={isSubmitted ? 'disabled' : ''}
          disabled={isSubmitted}
        >
          {isSubmitted && <AppDataLoader/>}
          Save
        </CButton>
      </div>
    </CForm>
  </>
}
const SchoolProfile = () => {
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
          />
        </CCardBody>
      </CCard>
    </>
  )
}
export default React.memo(SchoolProfile)
