import { cilLockLocked, cilUser } from '@coreui/icons'
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
import { Link, useNavigate } from 'react-router-dom'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import { submitForm } from '../../../App/Helper/SubmitForm'
import { CssHelper } from '../../../App/Helper/CssHelper'
import AppSwitchSectionBtn from '../../../App/Common/AppSwitchSectionBtn'

const Login = ({ onSubmit, state, formId }) => {
  const emailInputRef = useRef(null)
  const passInputRef = useRef(null)
  const defaultUserEmail = 'student@example.com'
  const defaultUserPass = 'student'

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
                   <CForm method="post" id={formId} onSubmit={onSubmit}>
                     <h1>Login</h1>
                     <p className="text-medium-emphasis">Sign in to your <b>student</b> account or </p>
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
                        ref={emailInputRef}
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
                        ref={passInputRef}
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
  const formId = 'login-form'
  const onSubmit = (event) => {
    submitForm({
      event,
      state,
      setState,
      formId,
      url: window.abeApp.urls.student_login,
      onSuccess
    })
  }

  return <Login
   onSubmit={onSubmit}
   state={state}
   formId={formId}/>
}

export default LoginPage
