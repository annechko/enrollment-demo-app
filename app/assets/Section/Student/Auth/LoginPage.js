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
import { CssHelper } from '../../../App/Helper/CssHelper'
import AppSwitchSectionBtn from '../../../App/Common/AppSwitchSectionBtn'
import { submitData } from '../../../App/Helper/Api'

const Login = ({ onSubmit, state }) => {
  const refEmail = useRef(null)
  const refPassword = useRef(null)
  const defaultUserEmail = 'student@example.com'
  const defaultUserPass = 'student'

  const fillDefaultUser = () => {
    refEmail.current.value = defaultUserEmail
    refPassword.current.value = defaultUserPass
  }

  const login = () => {
    onSubmit({
      email: refEmail.current.value,
      password: refPassword.current.value,
    })
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
                    <CForm>
                      <h1>Login</h1>
                      <p className="text-medium-emphasis">Sign in to your <b>student</b> account or</p>
                      <p className="text-medium-emphasis">
                        <CButton color="light" className="px-4 mb-4"
                          onClick={fillDefaultUser}>
                          Use default user
                        </CButton>
                      </p>
                      <AppErrorMessage error={state.error}/>
                      <CInputGroup className="mb-3">
                        <CInputGroupText>
                          <CIcon icon={cilUser}/>
                        </CInputGroupText>
                        <CFormInput placeholder="Email"
                          data-testid="email"
                          ref={refEmail}
                          autoComplete="email"
                          type="email"
                          required
                        />
                      </CInputGroup>
                      <CInputGroup className="mb-4">
                        <CInputGroupText>
                          <CIcon icon={cilLockLocked}/>
                        </CInputGroupText>
                        <CFormInput placeholder="Password"
                          data-testid="pass"
                          ref={refPassword}
                          type="password"
                          autoComplete="current-password" required
                        />
                      </CInputGroup>
                      <CRow>
                        <CCol xs={6}>
                          <CButton color="primary" className="px-4"
                            data-testid="btn-submit"
                            disabled={state.loading}
                            onClick={login}>
                            Login
                          </CButton>
                        </CCol>
                      </CRow>
                    </CForm>
                  </CCardBody>
                </CCard>
                <CCard className={'text-white py-5 ' + CssHelper.getCurrentSectionBgColor()}>
                  <CCardBody className="text-center">
                    <div>
                      <h2>Sign up</h2>
                      <p>Our user-friendly platform simplifies the application process and makes it easy for you to find Schools and apply to study.</p>
                      <Link to={window.abeApp.urls.student_register}>
                        <CButton color="light" variant="outline" className={'mt-3 '} tabIndex={-1}>
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
const LoginPage = () => {
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = React.useState(initialState)

  const navigate = useNavigate()
  const onSuccess = (response) => {
    navigate(window.abeApp.urls.student_home)
  }

  const onSubmit = (data) => {
    submitData({
      state,
      setState,
      data,
      url: window.abeApp.urls.student_login,
      onSuccess
    })
  }

  return <Login
    onSubmit={onSubmit}
    state={state}
  />
}

export default LoginPage
