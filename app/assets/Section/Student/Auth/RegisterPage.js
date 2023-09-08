import {
  CButton,
  CCard,
  CCardBody,
  CCol,
  CContainer,
  CForm,
  CFormInput,
  CFormLabel,
  CRow
} from '@coreui/react'
import React, { useRef } from 'react'
import { Link } from 'react-router-dom'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import * as LoadState from '../../../App/Helper/LoadState'
import { CssHelper } from '../../../App/Helper/CssHelper'
import AppSwitchSectionBtn from '../../../App/Common/AppSwitchSectionBtn'
import PropTypes from 'prop-types'
import { submitData } from '../../../App/Helper/Api'

const RegisterForm = ({ onSubmit, state }) => {
  const refName = useRef(null)
  const refSurname = useRef(null)
  const refEmail = useRef(null)
  const refPassword = useRef(null)

  const register = () => {
    onSubmit({
      name: refName.current.value,
      surname: refSurname.current.value,
      email: refEmail.current.value,
      plainPassword: refPassword.current.value,
    })
  }
  return (
    <>
      <h3>Register as student</h3>

      <CForm>
        <AppErrorMessage error={state.error}/>

        <div className="mb-3">
          <CFormLabel htmlFor="name">Name</CFormLabel>
          <CFormInput type="text" id="name"
            ref={refName}
            data-testid="name"
            minLength="1"
            required={true}
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="surname">Surname</CFormLabel>
          <CFormInput type="text" id="surname"
            ref={refSurname}
            data-testid="surname"
            minLength="1"
            required={true}
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="email">Email</CFormLabel>
          <CFormInput type="email" id="email"
            ref={refEmail}
            data-testid="email"
            minLength="1"
            required={true}
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="password">Password</CFormLabel>
          <CFormInput type="password" id="password"
            ref={refPassword}
            data-testid="pass"
            minLength="1"
            required={true}
          />
        </div>

        <div className="d-grid">
          <CButton className={'px-4 ' + CssHelper.getCurrentSectionBgColor()}
            disabled={state.loading}
            data-testid="btn-submit"
            onClick={register}>
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
  return <div data-testid="success-msg">
    Thank you! Please check your email to verify your email address.
  </div>
}
const Register = ({ onSubmit, state }) => {
  return (
    <>
      <div className="bg-light min-vh-100 d-flex flex-row align-items-center">
        <CContainer>
          <CRow className="justify-content-center">
            <CCol xl={6}>
              <AppSwitchSectionBtn/>
              <CCard>
                <CCardBody className="p-4">
                  {state?.registered === true
                    ? <AfterRegisterMessage/>
                    : <RegisterForm
                      onSubmit={onSubmit}
                      state={state}
                    />
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
    setState({ ...LoadState.finishLoading(), ...response.data, registered: true })
  }

  const onSubmit = (data) => {
    submitData({
      state,
      setState,
      data,
      url: window.abeApp.urls.api_student_register,
      onSuccess,
    })
  }
  return <Register state={state} onSubmit={onSubmit}/>
}
Register.propTypes = {
  onSubmit: PropTypes.func,
  state: PropTypes.shape({
    registered: PropTypes.bool,
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string
  }),
}
RegisterForm.propTypes = {
  onSubmit: PropTypes.func,
  state: PropTypes.shape({
    registered: PropTypes.bool,
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string
  }),
}
export default RegisterPage
