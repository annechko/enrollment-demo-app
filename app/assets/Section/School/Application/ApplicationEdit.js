import {useParams} from "react-router-dom";
import React, {useState} from "react";
import Loadable from "../../../App/Helper/Loadable";
import AppBackButton from "../../../App/Common/AppBackButton";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CFormInput,
  CFormLabel,
  CSpinner
} from "@coreui/react";
import * as LoadState from "../../../App/Helper/LoadState";

const ApplicationEdit = () => {
  const params = useParams()

  return <Loadable
    component={ApplicationView}
    url={window.abeApp.urls.api_school_application.replace(':applicationId', params.applicationId)}
  />
}
const ApplicationView = ({dataState}) => {
  const [acceptRequestState, setAcceptRequestState] = useState(LoadState.initialize)
  const [declineRequestState, setDeclineRequestState] = useState(LoadState.initialize)

  const onDeclineClick = () => {
  }
  const onAcceptClick = () => {
  }
  const item = dataState?.data || null
  if (!dataState.loaded) {
    if (dataState.error !== null) {
      return <>
        <AppBackButton/>
        <AppErrorMessage error={dataState.error}/>
      </>
    }
    if (item === null) {
      return (
        <>
          <AppBackButton/>
          <div>Loading...</div>
        </>
      )
    }
  }

  return (
    <>
      <AppBackButton/>
      <CCard className="mb-4">
        <CCardHeader>
          <strong>
            Application
          </strong>
        </CCardHeader>
        <CCardBody>
          <h5>Student</h5>
          <div className="mb-3">
            <CFormLabel htmlFor="fullName">Name</CFormLabel>
            <CFormInput
              readOnly
              defaultValue={item.student.fullName}
              type="text"
              id="fullName"
            />
          </div>
          <div className="mb-3">
            <CFormLabel htmlFor="preferredName">Preferred name</CFormLabel>
            <CFormInput
              readOnly
              defaultValue={item.student.preferredName}
              type="text"
              id="preferredName"
            />
          </div>
          <div className="mb-3">
            <CFormLabel htmlFor="dateOfBirth">Date Of Birth</CFormLabel>
            <CFormInput
              readOnly
              defaultValue={item.student.dateOfBirth}
              type="text"
              id="dateOfBirth"
            />
          </div>
          <div className="mb-3">
            <CFormLabel htmlFor="passportNumber">Passport Number</CFormLabel>
            <CFormInput
              readOnly
              defaultValue={item.student.passportNumber}
              type="text"
              id="passportNumber"
            />
          </div>
          <div className="mb-3">
            <CFormLabel htmlFor="passportExpiry">Passport Expiry</CFormLabel>
            <CFormInput
              readOnly
              defaultValue={item.student.passportExpiry}
              type="text"
              id="passportExpiry"
            />
          </div>

          <CButton color="success" size="sm"
            onClick={onAcceptClick}
            className={'px-4 me-3' + (acceptRequestState.loading ? ' disabled' : '')}
            disabled={acceptRequestState.loading}
            type="submit">
            {acceptRequestState.loading &&
              <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
            Accept
          </CButton>

          <CButton color="danger" size="sm"
            onClick={onDeclineClick}
            className={'px-4' + (declineRequestState.loading ? ' disabled' : '')}
            disabled={declineRequestState.loading}
            type="submit">
            {declineRequestState.loading &&
              <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
            Decline
          </CButton>
        </CCardBody>
      </CCard>
    </>
  )
}

export default ApplicationEdit
