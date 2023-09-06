import {
  cilLockLocked,
  cilUser
} from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import {
  CButton,
  CCard,
  CCardBody,
  CCardGroup,
  CCol,
  CContainer,
  CForm,
  CFormInput,
  CInputGroup,
  CInputGroupText,
  CRow
} from '@coreui/react'
import React, { useRef } from 'react'
import {
  Link,
  useNavigate
} from 'react-router-dom'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import { submitForm } from '../../../App/Helper/SubmitForm'
import AppSwitchSectionBtn from '../../../App/Common/AppSwitchSectionBtn'

const Login = ({ onSubmit, state, urlRegister, formId }) => {
  const emailInputRef = useRef(null)
  const passInputRef = useRef(null)
  const defaultUserEmail = 'school@example.com'
  const defaultUserPass = 'school'

  const fillDefaultUser = () => {
    emailInputRef.current.value = defaultUserEmail
    passInputRef.current.value = defaultUserPass
  }
  return (
    <>
      <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
        <CContainer>
          <CRow className="justify-content-center">
            <CCol md={8}>
              <AppSwitchSectionBtn/>
              <CCardGroup>
                <CCard className="p-4">
                  <CCardBody>
                    <CForm id={formId}>
                      <h1>Login</h1>
                      <p className="text-medium-emphasis">Sign in to your <b>school</b> account or
                      </p>
                      <CButton color="light" className="px-4 mb-3"
                        onClick={fillDefaultUser}>
                        Use default
                      </CButton>
                      <AppErrorMessage error={state.error}/>
                      <CInputGroup className="mb-3">
                        <CInputGroupText>
                          <CIcon icon={cilUser}/>
                        </CInputGroupText>
                        <CFormInput placeholder="Email"
                          ref={emailInputRef}
                          autoComplete="email"
                          data-testid="email"
                          name="email"
                          type="email"
                          required
                        />
                      </CInputGroup>
                      <CInputGroup className="mb-4">
                        <CInputGroupText>
                          <CIcon icon={cilLockLocked}/>
                        </CInputGroupText>
                        <CFormInput placeholder="Password"
                          ref={passInputRef}
                          type="password"
                          data-testid="pass"
                          name="password"
                          autoComplete="current-password" required
                        />
                      </CInputGroup>
                      <CRow>
                        <CCol xs={6}>
                          <CButton color="primary" className="px-4"
                            disabled={state.loading}
                            onClick={onSubmit}
                            data-testid="btn-submit"
                            >
                            Login
                          </CButton>
                        </CCol>
                      </CRow>
                    </CForm>
                  </CCardBody>
                </CCard>
                <CCard className="text-white bg-primary py-5">
                  <CCardBody className="text-center">
                    <div>
                      <h2>Sign up</h2>
                      <p>Our user-friendly platform simplifies the application process and makes it easy for your staff to manage and process applications.</p>
                      <Link to={urlRegister}>
                        <CButton color="primary" className="mt-3" active tabIndex={-1}>
                          Register Now!
                        </CButton>
                      </Link>
                    </div>
                  </CCardBody>
                </CCard>
              </CCardGroup>
            </CCol>
          </CRow>
        </CContainer>
      </div>
    </>
  )
}
const LoginPage = ({ urls }) => {
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = React.useState(initialState)

  const navigate = useNavigate()
  const onSuccess = (response) => {
    navigate(response.data?.redirect || '/')
  }
  const formId = 'login-form'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      url: urls.school_login,
      onSuccess
    })
  }

  return <Login
    onSubmit={onSubmit}
    state={state}
    urlRegister={urls.school_register}
    formId={formId}/>
}

export default LoginPage
