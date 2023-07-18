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
  CRow,
} from '@coreui/react'
import React from 'react'
import {useNavigate} from 'react-router-dom'
import {submitForm} from "../../../App/Helper/SubmitForm";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";

const Login = ({onSubmit, state, formId}) => {
  return (
    <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
      <CContainer>
        <CRow className="justify-content-center">
          <CCol md={8}>
            <CCardGroup>
              <CCard className="p-4">
                <CCardBody>
                  <CForm method="post" id={formId} onSubmit={onSubmit}>
                    <h1>Login</h1>
                    <p className="text-medium-emphasis">Sign In to your account</p>
                    <AppErrorMessage error={state.error}/>
                    <CInputGroup className="mb-3">
                      <CInputGroupText>
                        <CIcon icon={cilUser}/>
                      </CInputGroupText>
                      <CFormInput placeholder="Email"
                        autoComplete="email"
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
                        type="password"
                        name="password"
                        autoComplete="current-password" required
                      />
                    </CInputGroup>
                    <CRow>
                      <CCol xs={6}>
                        <CButton color="primary" className="px-4"
                          disabled={state.loading}
                          type="submit">
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
  )
}
const LoginPage = () => {
  const initialState = {
    loading: false,
    error: null
  }
  const [state, setState] = React.useState(initialState)

  const navigate = useNavigate();
  const onSuccess = (response) => {
    navigate(response.data?.redirect || '/');
  }
  const formId = 'login-form'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      url: window.abeApp.urls.admin_login,
      onSuccess: onSuccess
    })
  }

  return <Login
    onSubmit={onSubmit}
    state={state}
    formId={formId}/>
}

export default LoginPage