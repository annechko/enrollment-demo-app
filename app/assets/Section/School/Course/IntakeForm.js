import {
  CButton,
  CForm,
  CFormInput,
  CFormLabel,
  CFormSelect
} from '@coreui/react'
import PropTypes from 'prop-types'
import React, { useRef } from 'react'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import AppDataLoader from '../../../App/Common/AppDataLoader'
import { submitData } from '../../../App/Helper/Api'

const IntakeForm = ({
  formId,
  dataState,
  campusOptions,
  onSuccess,
  courseId,
  isUpdate = false,
  showSubmitBtn = true
}) => {
  const [submitState, setSubmitState] = React.useState({
    loading: false,
    error: null
  })
  const refName = useRef(null)
  const refStartDate = useRef(null)
  const refEndDate = useRef(null)
  const refCampusId = useRef(null)
  const refClassSize = useRef(null)

  const isSubmitted = submitState.loading
  const item = dataState?.data || null
  const error = submitState.error || dataState?.error || null
  if (isUpdate && item === null) {
    if (error !== null) {
      return <AppErrorMessage error={error}/>
    }
    return (
      <>
        <div>Loading...</div>
      </>
    )
  }
  const url = isUpdate
    ? window.abeApp.urls.api_school_course_intake_edit
      .replace(':courseId', courseId)
      .replace(':intakeId', item.id)
    : window.abeApp.urls.api_school_course_intake_add
      .replace(':courseId', courseId)
  const onSubmit = () => {
    submitData({
      state: submitState,
      setState: setSubmitState,
      data: {
        intakeId: item?.id,
        name: refName.current.value,
        startDate: refStartDate.current.value,
        endDate: refEndDate.current.value,
        classSize: refClassSize.current.value,
        campusId: refCampusId.current.value,
        courseId: courseId,
      },
      url,
      onSuccess
    })
  }
  return (
    <>
      <CForm data-testid="intake-form">
        <AppErrorMessage error={error}/>
        <div className="mb-3">
          <CFormLabel htmlFor="intakeName">Intake name</CFormLabel>
          <CFormInput ref={refName}
            data-testid="intake-name"
            defaultValue={isUpdate ? item.name : ''}
            type="text"
            id="intakeName"
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="startDate">Start date</CFormLabel>
          <CFormInput id="startDate"
            data-testid="intake-start"
            ref={refStartDate}
            defaultValue={isUpdate ? item.startDate : ''}
            type="date"
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="endDate">End date</CFormLabel>
          <CFormInput id="endDate"
            data-testid="intake-end"
            ref={refEndDate}
            defaultValue={isUpdate ? item.endDate : ''}
            type="date"
          />
        </div>

        <div className="mb-3">
          <CFormLabel htmlFor="campus">Main campus</CFormLabel>
          <CFormSelect id="campus" aria-label="Choose campus"
            data-testid="select-campus"
            defaultValue={isUpdate ? item.campus : null}
            options={campusOptions}
            ref={refCampusId}
          >
          </CFormSelect>
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="classSize">Class size</CFormLabel>
          <CFormInput id="classSize" data-testid="intake-class-size"
            type="number"
            defaultValue={isUpdate ? item.classSize : ''}
            rows="3"
            ref={refClassSize}></CFormInput>
        </div>
        {showSubmitBtn && (
          <CButton color="success" size="sm"
            data-testid="btn-submit"
            onClick={onSubmit}
            className={'px-4' + (isSubmitted ? ' disabled' : '')}
            disabled={isSubmitted}
          >
            {isSubmitted && <AppDataLoader/>}
            Save
          </CButton>)
        }
      </CForm>
    </>
  )
}
IntakeForm.propTypes = {
  isUpdate: PropTypes.bool,
  showSubmitBtn: PropTypes.bool,
  courseId: PropTypes.string.isRequired,
  formId: PropTypes.string.isRequired,
  onSuccess: PropTypes.func,
  campusOptions: PropTypes.arrayOf(
    PropTypes.shape({
      value: PropTypes.string.isRequired,
      label: PropTypes.string.isRequired
    })
  ),
  dataState: PropTypes.shape({
    data: PropTypes.shape({
      id: PropTypes.string,
      name: PropTypes.string,
      campus: PropTypes.string,
      startDate: PropTypes.string,
      endDate: PropTypes.string,
      classSize: PropTypes.number
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string
  })
}
export default IntakeForm
