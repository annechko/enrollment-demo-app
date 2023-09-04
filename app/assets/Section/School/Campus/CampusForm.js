import {
  CButton,
  CForm,
  CFormInput,
  CFormLabel,
  CFormTextarea
} from '@coreui/react'
import PropTypes from 'prop-types'
import React, { useRef } from 'react'
import AppBackButton from '../../../App/Common/AppBackButton'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import AppDataLoader from '../../../App/Common/AppDataLoader'

const CampusForm = ({
  onSubmit,
  isSubmitted,
  submitError,
  dataState,
  isUpdate = false,
  showSubmitBtn = true
}) => {
  const refName = useRef(null)
  const refAddress = useRef(null)
  const item = dataState?.data || null
  const error = submitError || dataState?.error || null
  if (isUpdate && item === null) {
    return (
      <>
        <AppBackButton/>
        <div>Loading...</div>
      </>
    )
  }
  const submit = () => {
    onSubmit({
      name: refName.current.value,
      address: refAddress.current.value,
    })
  }
  return (
    <>
      <CForm>
        <AppErrorMessage error={error}/>
        <div className="mb-3">
          <CFormLabel htmlFor="campusName">Campus name</CFormLabel>
          <CFormInput
            data-testid="campus-name"
            ref={refName}
            defaultValue={isUpdate ? item.name : ''}
            type="text"
            id="campusName"
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="campusAddress">Campus address</CFormLabel>
          <CFormTextarea id="campusAddress"
            data-testid="campus-address"
            ref={refAddress}
            defaultValue={isUpdate ? item.address : ''}
            rows="3"
          ></CFormTextarea>
        </div>
        {showSubmitBtn && (
          <CButton color="success" size="sm"
            onClick={submit}
            data-testid="btn-submit"
            className={'px-4' + (isSubmitted ? ' disabled' : '')}
            disabled={isSubmitted === true}
          >
            {isSubmitted && <AppDataLoader/>}
            Save
          </CButton>)
        }
      </CForm>
    </>
  )
}
CampusForm.propTypes = {
  isUpdate: PropTypes.bool,
  showSubmitBtn: PropTypes.bool,
  onSubmit: PropTypes.func.isRequired,
  isSubmitted: PropTypes.bool,
  submitError: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.oneOf([null])
  ]),
  dataState: PropTypes.shape({
    data: PropTypes.shape({
      name: PropTypes.string.isRequired,
      address: PropTypes.string
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string
  })
}
export default CampusForm
