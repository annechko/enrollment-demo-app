import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CForm,
  CFormInput,
  CFormLabel,
  CFormTextarea,
  CSpinner,
} from '@coreui/react'
import PropTypes from "prop-types";
import React, {
  useEffect,
  useState
} from 'react'
import AppBackButton from "../../components/AppBackButton";
import AppErrorMessage from "../../components/AppErrorMessage";
import Intakes from "./Intakes";

const CourseForm = ({
                      onSubmit,
                      formId,
                      dataState,
                      isSubmitted,
                      submitError,
                      isUpdate = false
                    }) => {
  const course = dataState?.data || null
  const error = submitError || dataState?.error || null

  if (isUpdate && course === null) {
    if (error) {
      return <AppErrorMessage error={error}/>
    }
    return (
      <>
        <AppBackButton/>
        <div>Loading...</div>
      </>
    )
  }
  return (
    <>
      <AppBackButton/>
      <CCard className="mb-4">
        <CCardHeader>
          <strong>
            {isUpdate ? 'Update course' : 'Lets create new course!'}
          </strong>
        </CCardHeader>
        <CCardBody>
          <AppErrorMessage error={error}/>
          <CForm method="post" onSubmit={onSubmit} id={formId}>
            <div className="mb-3">
              <CFormLabel className="mb-0" htmlFor="courseName">Course name</CFormLabel>
              <CFormInput
                id="courseName"
                name={formId + "[name]"}
                defaultValue={isUpdate ? course.name : ''}
                type="text"
              />
            </div>
            <div className="mb-3">
              <CFormLabel className="mb-0" htmlFor="campusDescr">Course description</CFormLabel>
              <CFormTextarea id="campusDescr"
                defaultValue={isUpdate ? course.description : ''}
                rows="3"
                name={formId + "[description]"}></CFormTextarea>
            </div>

            <Intakes formId={formId}
              isLoading={dataState.loading}
              campuses={course?.campuses || []}
              intakes={course?.intakes || []}
            />
            <div className="mb-2 mt-3">
              <CButton color="success"
                key={crypto.randomUUID()}
                className={'px-4' + (isSubmitted ? ' disabled' : '')}
                disabled={isSubmitted === true}
                type="submit">
                {isSubmitted && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
                Save
              </CButton>
            </div>

          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}
CourseForm.propTypes = {
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
      name: PropTypes.string,
      description: PropTypes.string,
      campuses: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string.isRequired,
          name: PropTypes.string.isRequired,
        })
      ).isRequired,
      intakes: PropTypes.arrayOf(
        PropTypes.shape({
          name: PropTypes.string,
          classSize: PropTypes.number,
          startDate: PropTypes.string,
          endDate: PropTypes.string,
          campus: PropTypes.string,
        })
      ),
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default React.memo(CourseForm)
