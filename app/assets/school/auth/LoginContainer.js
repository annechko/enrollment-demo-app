import React from 'react'
import {Link} from 'react-router-dom'
import CIcon from '@coreui/icons-react'
import {cilLockLocked, cilUser} from '@coreui/icons'
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
import {submitForm} from "./helper/SubmitForm";

const Login = ({onSubmit, state, urlRegister, formName}) =>
{
	return (
		<div className="bg-light min-vh-100 d-flex flex-row align-items-center">
			<CContainer>
				<CRow className="justify-content-center">
					<CCol md={8}>
						<CCardGroup>
							<CCard className="p-4">
								<CCardBody>
									<CForm method="post" id={formName} onSubmit={onSubmit}>
										<h1>Login</h1>
										<p className="text-medium-emphasis">Sign In to your account</p>
										{
											state.error !== null ? (
												<div className="alert alert-danger">
													{state.error}
												</div>
											) : ''
										}
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
							<CCard className="text-white bg-primary py-5" style={{width: '44%'}}>
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
	)
}
const LoginContainer = ({urls}) =>
{
	const initialState = {
		loading: false,
		error: null
	}
	const [state, setState] = React.useState(initialState)

	const onSuccess = (response) =>
	{
		window.location.href = response.data?.redirect || '/'
	}
	const formName = 'login-form'
	const onSubmit = (event) =>
	{
		submitForm({
			event,
			state,
			setState,
			url: urls.login,
			formName: formName,
			onSuccess: onSuccess
		})
	}

	return <Login
		onSubmit={onSubmit}
		state={state}
		urlRegister={urls.register}
		formName={formName}/>
}

export default LoginContainer
