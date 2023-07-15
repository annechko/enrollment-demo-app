import {
  CCard,
  CCardBody,
  CCardHeader,
} from '@coreui/react'
import PropTypes from "prop-types";
import React from 'react'
import AppBackButton from "../../Common/AppBackButton";
import AppErrorMessage from "../../../Common/AppErrorMessage";
import CampusForm from "./CampusForm";

const CampusView = ({onSubmit, formId, dataState, isSubmitted, submitError, isUpdate = false}) => {
  const item = dataState?.data || null
  const error = submitError || dataState?.error || null
  if (isUpdate) {
    if (dataState?.error !== null) {
      return <>
        <AppBackButton/>
        <AppErrorMessage error={dataState?.error}/>
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
            {isUpdate ? 'Update campus' : 'Lets create new campus!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <AppErrorMessage error={error}/>
          <CampusForm formId={formId} onSubmit={onSubmit} isSubmitted={isSubmitted}
            submitError={submitError} isUpdate={isUpdate} dataState={dataState}
          />
        </CCardBody>
      </CCard>
    </>
  )
}
CampusView.propTypes = {
  isUpdate: PropTypes.bool,
  onSubmit: PropTypes.func.isRequired,
  formId: PropTypes.string.isRequired,
  isSubmitted: PropTypes.bool,
  submitError: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.oneOf([null]),
  ]),
  dataState: PropTypes.shape({
    data: PropTypes.shape({
      name: PropTypes.string.isRequired,
      address: PropTypes.string
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default CampusView
