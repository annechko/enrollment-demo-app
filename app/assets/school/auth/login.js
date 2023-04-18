import React, {Suspense} from 'react'

import {Link} from 'react-router-dom'
import ReactDOM from 'react-dom/client';
import './login.scss';
import CIcon from '@coreui/icons-react'
import {cilLockLocked, cilUser, cilSchool} from '@coreui/icons'
import {BrowserRouter, Route, Routes} from 'react-router-dom'

const axios = require('axios');
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
	CFormFeedback,
	CInputGroupText,
	CRow,
} from '@coreui/react'

const loading = (
	<div className="pt-3 text-center">
		<div className="sk-spinner sk-spinner-pulse"></div>
	</div>
)
const URL_LOGIN = '/school/login'
const URL_REGISTER = '/school/register'

function submitForm({event, state, setState, url, formName, onSuccess, headers})
{
	event.preventDefault()
	if (state.loading)
	{
		return;
	}

	setState({
		loading: true,
		error: null
	})
	axios.post(url, document.getElementById(formName), {
		headers: headers || {
			'Content-Type': 'application/json'
		}
	})
		.then(response =>
		{
			setState({
				loading: false,
				error: null
			})
			if (onSuccess)
			{
				onSuccess(response)
			}
		})
		.catch((error) =>
		{
			setState({
				loading: false,
				error: error.response?.data?.error || 'Something went wrong'
			})
		});
}

const Login = () =>
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
	const onLogin = (event) =>
	{
		submitForm({
			event,
			state,
			setState,
			url: URL_LOGIN,
			formName: 'login-form',
			onSuccess: onSuccess
		})
	}

	return (
		<div className="bg-light min-vh-100 d-flex flex-row align-items-center">
			<CContainer>
				<CRow className="justify-content-center">
					<CCol md={8}>
						<CCardGroup>
							<CCard className="p-4">
								<CCardBody>
									<CForm method="post" id="login-form" onSubmit={onLogin}>
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
										<Link to={URL_REGISTER}>
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
const Register = () =>
{
	const initialState = {
		loading: false,
		error: null,
		registered: false
	}
	const [state, setState] = React.useState(initialState)
	const onSuccess = (response) =>
	{
		setState({
			loading: false,
			error: null,
			registered: true
		})
	}
	const onRegister = (event) =>
	{
		submitForm({
			event, state, setState, url: URL_REGISTER, formName: 'register-form',
			onSuccess: onSuccess,
			headers: {'Content-Type': 'multipart/form-data'}
		})
	}
	return (
		<div className="bg-light min-vh-100 d-flex flex-row align-items-center">
			<CContainer>
				<CRow className="justify-content-center">
					<CCol md={9} lg={7} xl={6}>
						<CCard className="mx-4">
							<CCardBody className="p-4">
								{
									state.registered === true ? (
											<>
												<h3>Thank you!</h3>
												<p className="text-medium-emphasis">We are glad to see you joined us as a school!</p>
												<p className="text-medium-emphasis">Kindly await an email containing an invitation to register.</p>

											</>) :
										(
											<>
												<h1>Register</h1>
												<p className="text-medium-emphasis">Set up your school account</p>
												<CForm method="post" id="register-form" onSubmit={onRegister}>

													{
														state.error !== null ? (
															<div className="alert alert-danger">
																{state.error}
															</div>
														) : ''
													}
													<CInputGroup className="mb-3">
														<CInputGroupText>
															<CIcon icon={cilSchool}/>
														</CInputGroupText>
														<CFormInput placeholder="School name"
															name="register[name]"
															minLength="2"
															required={true}
														/>
													</CInputGroup>
													<CInputGroup className="mb-3">
														<CInputGroupText>@</CInputGroupText>
														<CFormInput placeholder="Email address of account's owner"
															name="register[adminEmail]"
															type="email"
															required={true}
														/>
													</CInputGroup>
													<CInputGroup className="mb-3">
														<CInputGroupText>
															<CIcon icon={cilUser}/>
														</CInputGroupText>
														<CFormInput
															name="register[adminName]"
															placeholder="Admin name"
															required={true}
														/>
													</CInputGroup>
													<CInputGroup className="mb-4">
														<CInputGroupText>
															<CIcon icon={cilUser}/>
														</CInputGroupText>
														<CFormInput
															name="register[adminSurname]"
															placeholder="Admin surname"
															required={true}
														/>
													</CInputGroup>
													<div className="d-grid">
														<CButton color="success" className="px-4"
															disabled={state.loading}
															type="submit">
															Create Account
														</CButton>
													</div>
													<Link to={URL_LOGIN}>
														<div className="text-center m-1">
															Already have an account?
														</div>
													</Link>
												</CForm>
											</>
										)
								}

							</CCardBody>
						</CCard>
					</CCol>
				</CRow>
			</CContainer>
		</div>
	)
}

function DefaultLayout()
{
	return <div>DefaultLayout?</div>
}

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
	<BrowserRouter>
		<Suspense fallback={loading}>
			<Routes>
				<Route exact path={URL_LOGIN} name="Login Page" element={<Login/>}/>
				<Route exact path={URL_REGISTER} name="Register Page" element={<Register/>}/>

				<Route path="*" name="Home" element={<DefaultLayout/>}/>
			</Routes>
		</Suspense>
	</BrowserRouter>
);
