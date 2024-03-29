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
import { useNavigate } from 'react-router-dom'
import { submitForm } from '../../../App/Helper/SubmitForm'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import AppSwitchSectionBtn from '../../../App/Common/AppSwitchSectionBtn'
import AppDataLoader from '../../../App/Common/AppDataLoader'

const Login = ({ onSubmit, state, formId }) => {
  const emailInputRef = useRef(null)
  const passInputRef = useRef(null)
  const defaultUserEmail = 'admin@example.com'
  const defaultUserPass = 'admin'

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
                    <CForm method="post" id={formId}>
                      <h1>Login</h1>
                      <p className="text-medium-emphasis">You can not register a new <b>admin</b> account.</p>
                      <CButton color="light" className="px-4 mb-4"
                        onClick={fillDefaultUser}>
                        Use default user
                      </CButton>
                      <AppErrorMessage error={state.error}/>
                      <CInputGroup className="mb-3">
                        <CInputGroupText>
                          <CIcon icon={cilUser}/>
                        </CInputGroupText>
                        <CFormInput placeholder="Email"
                          required
                          data-testid="email"
                          ref={emailInputRef}
                          autoComplete="email"
                          name="email"
                          type="email"
                        />
                      </CInputGroup>
                      <CInputGroup className="mb-4">
                        <CInputGroupText>
                          <CIcon icon={cilLockLocked}/>
                        </CInputGroupText>
                        <CFormInput placeholder="Password"
                          required
                          data-testid="pass"
                          type="password"
                          ref={passInputRef}
                          name="password"
                          autoComplete="current-password"
                        />
                      </CInputGroup>
                      <CRow>
                        <CCol xs={4}>
                          <CButton color="danger" className="px-4"
                            onClick={onSubmit}
                            disabled={state.loading}
                            data-testid="btn-submit"
                          >{state.loading && <AppDataLoader/>}
                            Login
                          </CButton>
                        </CCol>

                      </CRow>
                    </CForm>
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
    navigate(response.data?.redirect || '/')
  }
  const formId = 'login-form'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      url: window.abeApp.urls.admin_login,
      onSuccess
    })
  }

  return <Login
    onSubmit={onSubmit}
    state={state}
    formId={formId}/>
}

export default LoginPage
