import React, {Suspense} from 'react'

import {Link} from 'react-router-dom'
import ReactDOM from 'react-dom/client';
import './styles/login.scss';
import CIcon from '@coreui/icons-react'
import {cilLockLocked, cilUser} from '@coreui/icons'
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

function runLogin(event, state, setState)
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
	axios.post(URL_LOGIN, document.getElementById('form-name'), {
		headers: {
			'Content-Type': 'application/json'
		}
	})
		.then(response =>
		{
			setState({
				loading: false,
				error: null
			})
			window.location.href = response.data?.redirect || '/'
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
	const test = (e) =>
	{
		runLogin(e, state, setState)
	}

	return (
		<div className="bg-light min-vh-100 d-flex flex-row align-items-center">
			<CContainer>
				<CRow className="justify-content-center">
					<CCol md={8}>
						<CCardGroup>
							<CCard className="p-4">
								<CCardBody>
									<CForm method="post" id="form-name" onSubmit={test}>
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
											<CFormInput
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
	return (
		<div className="bg-light min-vh-100 d-flex flex-row align-items-center">
			<CContainer>
				<CRow className="justify-content-center">
					<CCol md={9} lg={7} xl={6}>
						<CCard className="mx-4">
							<CCardBody className="p-4">
								<CForm>
									<h1>Register</h1>
									<p className="text-medium-emphasis">Create your account</p>
									<CInputGroup className="mb-3">
										<CInputGroupText>
											<CIcon icon={cilUser}/>
										</CInputGroupText>
										<CFormInput placeholder="Username" autoComplete="username"/>
									</CInputGroup>
									<CInputGroup className="mb-3">
										<CInputGroupText>@</CInputGroupText>
										<CFormInput placeholder="Email" autoComplete="email"/>
									</CInputGroup>
									<CInputGroup className="mb-3">
										<CInputGroupText>
											<CIcon icon={cilLockLocked}/>
										</CInputGroupText>
										<CFormInput
											type="password"
											placeholder="Password"
											autoComplete="new-password"
										/>
									</CInputGroup>
									<CInputGroup className="mb-4">
										<CInputGroupText>
											<CIcon icon={cilLockLocked}/>
										</CInputGroupText>
										<CFormInput
											type="password"
											placeholder="Repeat password"
											autoComplete="new-password"
										/>
									</CInputGroup>
									<div className="d-grid">
										<CButton color="success">Create Account</CButton>
									</div>
									<Link to={URL_LOGIN}>
										<div className="text-center m-1">
											Already have an account?
										</div>
									</Link>
								</CForm>
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
