import {
  cilSchool,
  cilUser
} from '@coreui/icons'
import CIcon from '@coreui/icons-react'
import {
  CButton,
  CCard,
  CCardBody,
  CCol,
  CContainer,
  CForm,
  CFormInput,
  CInputGroup,
  CInputGroupText,
  CRow
} from '@coreui/react'
import React, { useRef } from 'react'
import { Link } from 'react-router-dom'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import * as LoadState from '../../../App/Helper/LoadState'
import AppSwitchSectionBtn from '../../../App/Common/AppSwitchSectionBtn'
import AppDataLoader from '../../../App/Common/AppDataLoader'
import { submitData } from '../../../App/Helper/Api'

const AfterRegisterMessage = () => {
  return (
    <div data-testid="success-submit">
      <h3>Thank you!</h3>
      <p className="text-medium-emphasis">We are glad to see you joined us as a school!</p>
      <p className="text-medium-emphasis">Kindly await an email containing an invitation to register.</p>
      <p className="text-medium-emphasis">After our admin user approves your registration in admin panel.</p>
    </div>
  )
}
const RegisterForm = ({ onSubmit, state, urlLogin }) => {
  const refName = useRef(null)
  const refAdminName = useRef(null)
  const refAdminSurname = useRef(null)
  const refAdminEmail = useRef(null)
  const processRegister = () => {
    onSubmit({
      name: refName.current.value,
      adminName: refAdminName.current.value,
      adminSurname: refAdminSurname.current.value,
      adminEmail: refAdminEmail.current.value,
    })
  }
  return (
    <>
      <h1>Register</h1>
      <p className="text-medium-emphasis">Set up your school account</p>

      <CForm>
        <AppErrorMessage error={state.error}/>
        <CInputGroup className="mb-3">
          <CInputGroupText>
            <CIcon icon={cilSchool}/>
          </CInputGroupText>
          <CFormInput placeholder="School name"
            ref={refName}
            data-testid="name"
            minLength="2"
            required={true}
          />
        </CInputGroup>
        <CInputGroup className="mb-3">
          <CInputGroupText>@</CInputGroupText>
          <CFormInput placeholder="Email address of account owner"
            data-testid="email"
            ref={refAdminEmail}
            type="email"
            required={true}
          />
        </CInputGroup>
        <CInputGroup className="mb-3">
          <CInputGroupText>
            <CIcon icon={cilUser}/>
          </CInputGroupText>
          <CFormInput ref={refAdminName}
            data-testid="admin-name"
            placeholder="Admin name"
            required={true}
          />
        </CInputGroup>
        <CInputGroup className="mb-4">
          <CInputGroupText>
            <CIcon icon={cilUser}/>
          </CInputGroupText>
          <CFormInput ref={refAdminSurname}
            data-testid="admin-surname"
            placeholder="Admin surname"
            required={true}
          />
        </CInputGroup>
        <div className="d-grid">
          <CButton color="success" className="px-4"
            data-testid="btn-submit"
            onClick={processRegister}
            disabled={state.loading}
          >
            {state.loading && <AppDataLoader/>}
            Create Account
          </CButton>
        </div>
        <Link to={urlLogin}>
          <div className="text-center m-1">
            Already have an account?
          </div>
        </Link>
      </CForm>
    </>
  )
}
const Register = ({ onSubmit, state, urlLogin }) => {
  const isRegistered = state.registered === true
  return (
    <>
      <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
        <CContainer>
          <CRow className="justify-content-center">
            <CCol xl={6}>
              <AppSwitchSectionBtn/>
              <CCard>
                <CCardBody className="p-4">
                  {isRegistered
                    ? <AfterRegisterMessage/>
                    : <RegisterForm
                      onSubmit={onSubmit}
                      urlLogin={urlLogin}
                      state={state}
                      />}
                </CCardBody>
              </CCard>
            </CCol>
          </CRow>
        </CContainer>
      </div>
    </>
  )
}
const RegisterPage = ({ urls }) => {
  const [state, setState] = React.useState(LoadState.initialize())
  const onSuccess = (response) => {
    setState({ ...LoadState.finishLoading(), registered: true })
  }

  const onSubmit = (data) => {
    submitData({
      state,
      setState,
      url: urls.api_school_register,
      data,
      onSuccess,
    })
  }
  return <Register urlLogin={urls.school_login} state={state} onSubmit={onSubmit}/>
}
export default RegisterPage
