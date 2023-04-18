import React from 'react'
import {Link} from 'react-router-dom'
import CIcon from '@coreui/icons-react'
import {cilSchool, cilUser} from '@coreui/icons'
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
	CRow,
} from '@coreui/react'
import {submitForm} from "./SubmitForm";

const AfterRegisterMessage = () =>
{
	return (
		<>
			<h3>Thank you!</h3>
			<p className="text-medium-emphasis">We are glad to see you joined us as a school!</p>
			<p className="text-medium-emphasis">Kindly await an email containing an invitation to register.</p>
		</>
	)
}
const RegisterForm = ({onSubmit, state, urlLogin, formId}) =>
{
	return (
		<>
			<h1>Register</h1>
			<p className="text-medium-emphasis">Set up your school account</p>

			<CForm method="post" id={formId} onSubmit={onSubmit}>

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
				<Link to={urlLogin}>
					<div className="text-center m-1">
						Already have an account?
					</div>
				</Link>
			</CForm>
		</>
	)
}
const Register = ({onSubmit, state, urlLogin, formId}) =>
{
	const isRegistered = state.registered === true
	return (
		<div className="bg-light min-vh-100 d-flex flex-row align-items-center">
			<CContainer>
				<CRow className="justify-content-center">
					<CCol md={9} lg={7} xl={6}>
						<CCard className="mx-4">
							<CCardBody className="p-4">
								{isRegistered ? <AfterRegisterMessage/>
									: <RegisterForm
										onSubmit={onSubmit}
										urlLogin={urlLogin}
										state={state}
										formId={formId}/>}
							</CCardBody>
						</CCard>
					</CCol>
				</CRow>
			</CContainer>
		</div>
	)
}
const RegisterContainer = () =>
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
	const formId = 'register-form';
	const onSubmit = (event) =>
	{
		submitForm({
			event,
			state,
			setState,
			url: window.abeApp.URL_REGISTER,
			formId: formId,
			onSuccess: onSuccess,
			headers: {'Content-Type': 'multipart/form-data'}
		})
	}
	return <Register urlLogin={window.abeApp.URL_LOGIN} state={state} onSubmit={onSubmit} formId={formId}/>
}
export default RegisterContainer
