import {
  CButton,
  CCard,
  CCardBody,
  CCol,
  CContainer,
  CForm,
  CFormInput,
  CFormLabel,
  CRow,
} from '@coreui/react'
import React, {useEffect} from 'react'
import {
  Link,
  useNavigate
} from 'react-router-dom'
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import {submitForm} from "../../../App/Helper/SubmitForm";
import * as LoadState from "../../../App/Helper/LoadState";
import {CssHelper} from "../../../App/Helper/CssHelper";
import CIcon from "@coreui/icons-react";
import {cilArrowLeft} from "@coreui/icons";

const RegisterForm = ({onSubmit, state, formId}) => {
  return (
    <>
      <h3>Register as student</h3>

      <CForm method="post" id={formId} onSubmit={onSubmit}>
        <AppErrorMessage error={state.error}/>

        <div className="mb-3">
          <CFormLabel htmlFor="name">Name</CFormLabel>
          <CFormInput type="text    " id="name"
            name="register[name]"
            minLength="1"
            required={true}
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="surname">Surname</CFormLabel>
          <CFormInput type="text" id="surname"
            name="register[surname]"
            minLength="1"
            required={true}
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="email">Email</CFormLabel>
          <CFormInput type="email" id="email"
            name="register[email]"
            minLength="1"
            required={true}
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="password">Password</CFormLabel>
          <CFormInput type="password" id="password"
            name="register[plainPassword]"
            minLength="1"
            required={true}
          />
        </div>

        <div className="d-grid">
          <CButton className={'px-4 ' + CssHelper.getCurrentSectionBgColor()}
            disabled={state.loading}
            type="submit">
            Create Account
          </CButton>
        </div>
        <Link to={window.abeApp.urls.student_login}>
          <div className="text-center m-1">
            Already have an account?
          </div>
        </Link>
      </CForm>
    </>
  )
}
const AfterRegisterMessage = () => {
  return <>
    Thank you! Please check your email to verify your email address.
  </>
}
const Register = ({onSubmit, state, formId}) => {
  const navigate = useNavigate();
  useEffect(() => {
    if (state?.registered === true) {
      if (state?.emailVerificationEnabled === false) {
        navigate(window.abeApp.urls.student_home)
      }
    }
  }, [state])

  return (
      <>
        <Link to={window.abeApp.urls.home}>
          <CButton color="dark" role="button" className="py-2 mb-2"
              size="sm"
              variant="outline">
            <CIcon icon={cilArrowLeft} className="me-2"/>
            Switch section
          </CButton>
        </Link>
        <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
          <CContainer>
            <CRow className="justify-content-center">
              <CCol md={9} lg={7} xl={6}>
                <CCard className="mx-4">
                  <CCardBody className="p-4">
                    {state?.emailVerificationEnabled === true
                        ? <AfterRegisterMessage/>
                        : <RegisterForm
                            onSubmit={onSubmit}
                            state={state}
                            formId={formId}/>
                    }
                  </CCardBody>
                </CCard>
              </CCol>
            </CRow>
          </CContainer>
        </div>
      </>
  )
}
const RegisterPage = () => {
  const [state, setState] = React.useState(LoadState.initialize())
  const onSuccess = (response) => {
    setState({...LoadState.finishLoading(), ...response.data, registered: true})
  }
  const formId = 'register-form';
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      url: window.abeApp.urls.api_student_register,
      onSuccess: onSuccess,
      headers: {'Content-Type': 'multipart/form-data'}
    })
  }
  return <Register state={state} onSubmit={onSubmit} formId={formId}/>
}
export default RegisterPage
