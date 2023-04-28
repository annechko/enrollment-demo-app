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
import CampusOptions from "./CampusOptions";
import CIcon from "@coreui/icons-react";
import {cilX} from "@coreui/icons";

const CourseForm = ({
                      onSubmit,
                      campusAddState,
                      setCampusAddState,
                      onCampusAdd,
                      reload,
                      setCampusValue,
                      formId,
                      dataState,
                      campusValue,
                      isSubmitted,
                      submitError,
                      isUpdate = false
                    }) => {
  const item = dataState?.data || null
  const error = submitError || dataState?.error || null

  const [startDatesState, setStartDatesState] = useState([null])
  useEffect(() => {
    if (isUpdate && dataState.loaded) {
      if (item?.startDates > 0) {
        setStartDatesState(Array.from(item?.startDates || [null]))
      }
    }
  }, [isUpdate, dataState.loaded])


  if (isUpdate && item === null) {
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
  const addStartDate = () => {
    let updated = Array.from(startDatesState)
    updated.push(null)
    setStartDatesState(updated)
  }
  const deleteStartDate = (dateIndex) => {
    if (startDatesState.length <= 1) {
      return;
    }
    let updated = Array.from(startDatesState)
    updated.splice(dateIndex, 1)
    setStartDatesState(updated)
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
              <CFormLabel htmlFor="courseName">Course name</CFormLabel>
              <CFormInput
                id="courseName"
                name={formId + "[name]"}
                defaultValue={isUpdate ? item.name : ''}
                type="text"
              />
            </div>
            <div className="mb-3">
              <CFormLabel htmlFor="campusDescr">Course description</CFormLabel>
              <CFormTextarea id="campusDescr"
                defaultValue={isUpdate ? item.description : ''}
                rows="3"
                name={formId + "[description]"}></CFormTextarea>
            </div>
            <div className="mb-3">
              <CampusOptions formId={formId}
                onCampusAdd={onCampusAdd}
                reload={reload}
                setCampusAddState={setCampusAddState}
                campusAddState={campusAddState}
                campusValue={campusValue !== null ? campusValue : item?.selectedCampuses || []}
                isLoading={dataState.loading}
                campuses={item?.campuses || []}
                setCampusValue={setCampusValue}/>
            </div>
            <div className="mb-3">
              <CFormLabel>Start dates</CFormLabel>
              <CButton variant="outline" size="sm" className="ms-1 py-0" color="primary"
                onClick={addStartDate}>+</CButton>
              {startDatesState.map((dateValue, dateIndex) => {
                return (
                  <div className="my-1 d-flex" key={dateIndex}>
                    <div className="align-self-center d-flex app-clickable">
                      <CIcon
                        icon={cilX} className="me-2" onClick={(e) => {
                        deleteStartDate(dateIndex)
                      }}/>
                    </div>
                    <CFormInput
                      name={formId + "[startDates][" + dateIndex + "]"}
                      defaultValue={dateValue}
                      type="date"
                    />
                  </div>
                )
              })}
            </div>
            <CButton color="success"
              className={'px-4' + (isSubmitted ? ' disabled' : '')}
              disabled={isSubmitted === true}
              type="submit">
              {isSubmitted && <CSpinner className="me-1" component="span" size="sm" aria-hidden="true"/>}
              Save
            </CButton>
          </CForm>
        </CCardBody>
      </CCard>
    </>
  )
}
CourseForm.propTypes = {
  isUpdate: PropTypes.bool,
  setCampusValue: PropTypes.func.isRequired,
  onCampusAdd: PropTypes.func.isRequired,
  onSubmit: PropTypes.func.isRequired,
  formId: PropTypes.string.isRequired,
  isSubmitted: PropTypes.bool,
  campusValue: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.arrayOf(
      PropTypes.string.isRequired
    ),
    PropTypes.oneOf([null]),
  ]),
  submitError: PropTypes.oneOfType([
    PropTypes.string,
    PropTypes.oneOf([null]),
  ]),
  campusAddState: PropTypes.shape({
    loading: PropTypes.bool,
    error: PropTypes.string,
  }),
  reload: PropTypes.func.isRequired,
  setCampusAddState: PropTypes.func.isRequired,
  dataState: PropTypes.shape({
    data: PropTypes.shape({
      name: PropTypes.string,
      description: PropTypes.string,
      selectedCampuses: PropTypes.arrayOf(
        PropTypes.string.isRequired
      ),
      startDates: PropTypes.arrayOf(
        PropTypes.string.isRequired
      ),
      campuses: PropTypes.arrayOf(
        PropTypes.shape({
          id: PropTypes.string.isRequired,
          name: PropTypes.string.isRequired,
        })
      ).isRequired,
    }),
    loading: PropTypes.bool,
    loaded: PropTypes.bool,
    error: PropTypes.string,
  }),
}
export default CourseForm
