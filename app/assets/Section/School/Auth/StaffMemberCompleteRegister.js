import React, { useState } from 'react'
import { CButton, CCol, CContainer, CForm, CFormInput, CRow } from '@coreui/react'
import * as LoadState from '../../../App/Helper/LoadState'
import * as Api from '../../../App/Helper/Api'
import { Link, useParams } from 'react-router-dom'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'

const StaffMemberCompleteRegister = () => {
  const params = useParams()
  const [state, setState] = useState(LoadState.initialize)
  const [isConfirmed, setIsConfirmed] = useState(false)

  const url = window.abeApp.urls.api_school_member_register
    .replace(':schoolId', params.schoolId)
    .replace(':invitationToken', params.invitationToken)

  const confirm = (e) => {
    e.preventDefault()
    Api.submitData({
      state,
      setState,
      url,
      data: document.getElementById('pass'),
      onSuccess: () => {
        setIsConfirmed(true)
      }
    })
  }

  return (
		<CContainer sm>
			<div className=" justify-content-md-center  min-vh-100 d-flex flex-row align-items-center row-cols-3">
				<CRow className="align-items-center">
					<CCol>
						{isConfirmed
						  ? <>
								<div className="d-flex flex-column align-items-center">
									<h4>
										Your account is ready!
									</h4>
									<div className="text-center">
										You can now login with your email and password!
									</div>
									<Link to={window.abeApp.urls.school_login}>
										<CButton color="success" role="button"
											size="lg"
											className=" pl-1 pr-1 mt-3"
										>Login
										</CButton>
									</Link>
								</div>
							</>
						  : <>
								<AppErrorMessage error={state?.error}/>

								<CForm id="pass" onSubmit={confirm}>
									<div className="mb-3 text-center">
										<h4>Set up your password</h4>
										<CFormInput id="passValue"
											name="plainPassword"
											autoComplete="off"
											type="password"
										/>
									</div>
									<div className="mb-3 text-center btn-lg">
										<CButton className="mb-3"
											onClick={confirm}
											disabled={state.loading}
										>
											Confirm
										</CButton>
									</div>
								</CForm>
							</>
						}
					</CCol>
				</CRow>
			</div>
		</CContainer>
  )
}

export default StaffMemberCompleteRegister
