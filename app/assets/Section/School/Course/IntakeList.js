import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader,
  CModal,
  CModalBody,
  CModalFooter,
  CModalHeader,
  CModalTitle,
  CTable,
  CTableBody,
  CTableDataCell,
  CTableHead,
  CTableHeaderCell,
  CTableRow
} from '@coreui/react'
import React, {
  useEffect,
  useState
} from 'react'
import CIcon from '@coreui/icons-react'
import {
  cilPencil,
  cilX
} from '@coreui/icons'
import Loadable from '../../../App/Helper/Loadable'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import IntakeForm from './IntakeForm'
import PropTypes from 'prop-types'
import * as LoadState from '../../../App/Helper/LoadState'
import * as Api from '../../../App/Helper/Api'
import { submitData } from '../../../App/Helper/Api'
import AppDataLoader from '../../../App/Common/AppDataLoader'

const IntakeList = ({ courseId }) => {
  const [newIntakeModalState, setNewIntakeModalState] = React.useState({ id: null, visible: false })
  const [removeIntakeState, setRemoveIntakeState] = React.useState({ modalVisible: false, intake: {} })
  const [removeIntakeRequestState, setRemoveIntakeRequestState] = useState(LoadState.initialize)

  const [campusesState, setCampusesState] = useState(LoadState.initialize())
  const loadCampusList = Api.loadData(window.abeApp.urls.api_school_campus_list, setCampusesState)
  useEffect(() => {
    if (LoadState.needLoading(campusesState)) {
      loadCampusList()
    }
  }, [campusesState])

  const [intakesState, setIntakesState] = useState(LoadState.initialize())
  const intakesUrl = window.abeApp.urls.api_school_course_intake_list.replace(':id', courseId)
  const loadIntakes = Api.loadData(intakesUrl, setIntakesState)
  useEffect(() => {
    if (LoadState.needLoading(intakesState)) {
      loadIntakes()
    }
  }, [intakesState])

  const campusOptions = [{
    value: '',
    label: campusesState.loading ? 'Loading...' : ''
  }]

  if (campusesState.error !== null || intakesState.error !== null) {
    return <AppErrorMessage error={campusesState.error}/>
  }
  const formId = 'intake'
  if (campusesState.loaded === true) {
    campusesState.data.forEach((item) => {
      campusOptions.push({
        value: item.id,
        label: item.name
      })
    })
  }
  const closeNewIntakeModal = () => { setNewIntakeModalState({ id: null, visible: false }) }
  const onNewIntakeModalExecuted = () => {
    closeNewIntakeModal()
    loadIntakes()
  }
  const intakeToRemove = removeIntakeState.intake
  const removeIntake = (intakeId) => {
    return () => {
      const url = window.abeApp.urls.api_school_course_intake_remove
        .replace(':courseId', courseId)
        .replace(':intakeId', intakeId)
      setRemoveIntakeRequestState(LoadState.startLoading())
      submitData({
        state: removeIntakeRequestState,
        setState: setRemoveIntakeRequestState,
        url,
        data: {},
        onSuccess: () => {
          loadIntakes()
          // todo add success alert
          setRemoveIntakeState({ modalVisible: false, intake: {} })
        }
      })
    }
  }
  return (
    <CCard className="mb-4">
      <CCardHeader>
        <strong>Intakes</strong>
      </CCardHeader>
      <CCardBody className="overflow-y-scroll">
        <IntakesRows
          intakesState={intakesState}
          setNewIntakeModalState={setNewIntakeModalState}
          newIntakeModalState={newIntakeModalState}
          setRemoveIntakeState={setRemoveIntakeState}
        />
        <CModal visible={newIntakeModalState.visible} onClose={closeNewIntakeModal} data-testid="intake-modal">
          <CModalHeader onClose={closeNewIntakeModal}>
            <CModalTitle>{newIntakeModalState.id === null ? 'Add new intake' : 'Edit intake'}</CModalTitle>
          </CModalHeader>
          <CModalBody>
            {newIntakeModalState.id === null
              ? <IntakeForm formId={formId}
                showSubmitBtn
                courseId={courseId}
                campusOptions={campusOptions}
                onSuccess={onNewIntakeModalExecuted}
              />
              : <Loadable component={IntakeForm}
                url={window.abeApp.urls.api_school_course_intake
                  .replace(':courseId', courseId).replace(':intakeId', newIntakeModalState.id)}
                isUpdate
                formId={formId}
                showSubmitBtn
                courseId={courseId}
                campusOptions={campusOptions}
                onSuccess={onNewIntakeModalExecuted}
              />
            }
          </CModalBody>
        </CModal>

        <CModal visible={removeIntakeState.modalVisible} data-testid="remove-intake-modal"
          onClose={() => {
            setRemoveIntakeState({ modalVisible: false, intake: {} })
          }}>
          <CModalHeader onClose={() => {
            setRemoveIntakeState({ modalVisible: false, intake: {} })
          }}>
            <CModalTitle>Remove intake</CModalTitle>
          </CModalHeader>
          <CModalBody>
            {removeIntakeRequestState.error !== null && <AppErrorMessage error={removeIntakeRequestState.error}/>}
            Are you sure you want to remove intake &quot;{intakeToRemove.name}&quot;?
            Dates: {intakeToRemove.start} - {intakeToRemove.end}
          </CModalBody>
          <CModalFooter>
            <CButton color="secondary" size="sm" onClick={() => {
              setRemoveIntakeState({
                modalVisible: false,
                intake: {}
              })
            }}>
              Close
            </CButton>
            <CButton color="danger" size="sm"
              data-testid="btn-modal-remove-intake"
              disabled={removeIntakeRequestState.loading === true}
              onClick={removeIntake(intakeToRemove.id)}>
              {removeIntakeRequestState.loading === true &&
                <AppDataLoader/>}
              Remove</CButton>
          </CModalFooter>
        </CModal>

      </CCardBody>
    </CCard>
  )
}
const IntakesRows = ({
  intakesState,
  setNewIntakeModalState,
  setRemoveIntakeState,
  newIntakeModalState
}) => {
  const intakes = intakesState.data

  let key = 0
  if (intakesState.error !== null) {
    return <AppErrorMessage error={intakesState.error}/>
  }
  if (intakesState.loaded === false) {
    return <AppDataLoader/>
  }
  let intakesRows = []

  intakesRows = intakes.map((item) =>
    <CTableRow key={key++}>
      <CTableDataCell scope="row">{item.id.substring(32)}</CTableDataCell>
      <CTableDataCell data-testid="cell-intake-name">{item.name}</CTableDataCell>
      <CTableDataCell data-testid="cell-intake-start" className="text-nowrap">{item.startDate}</CTableDataCell>
      <CTableDataCell data-testid="cell-intake-end" className="text-nowrap">{item.endDate}</CTableDataCell>
      <CTableDataCell data-testid="cell-intake-class-size">{item.classSize}</CTableDataCell>
      <CTableDataCell data-testid="cell-intake-campus">{item.campus}</CTableDataCell>
      <CTableDataCell>
        <div className="d-flex">
          <CButton color="primary" role="button"
            data-testid="btn-edit-intake"
            className="py-0 me-1"
            onClick={() => {
              setNewIntakeModalState({ id: item.id, visible: true })
            }}
            size="sm" variant="outline">
            <CIcon icon={cilPencil}/>
          </CButton>
          <CButton color="danger" role="button"
            data-testid="btn-remove-intake"
            className="py-0"
            onClick={() => {
              setRemoveIntakeState({
                modalVisible: true,
                intake: { name: item.name, id: item.id, start: item.startDate, end: item.endDate }
              })
            }}
            size="sm" variant="outline">
            <CIcon icon={cilX}/>
          </CButton>
        </div>
      </CTableDataCell>
    </CTableRow>
  )

  return <>
    {intakesRows.length > 0 &&
      <CTable hover bordered data-testid="intakes-table">
        <CTableHead>
          <CTableRow key={key++}>
            <CTableHeaderCell scope="col">Id</CTableHeaderCell>
            <CTableHeaderCell scope="col">Name</CTableHeaderCell>
            <CTableHeaderCell scope="col">startDate</CTableHeaderCell>
            <CTableHeaderCell scope="col">endDate</CTableHeaderCell>
            <CTableHeaderCell scope="col">classSize</CTableHeaderCell>
            <CTableHeaderCell scope="col">campus</CTableHeaderCell>
            <CTableHeaderCell scope="col">Actions</CTableHeaderCell>
          </CTableRow>
        </CTableHead>
        <CTableBody>
          {intakesRows}
        </CTableBody>
      </CTable>}
    <CButton color="primary" role="button" size="sm"
      data-testid="btn-add-intake"
      onClick={() => setNewIntakeModalState({ id: null, visible: !newIntakeModalState.visible })}
    >New</CButton>
  </>
}

IntakeList.propTypes = {
  courseId: PropTypes.string.isRequired
}
IntakesRows.propTypes = {
  setNewIntakeModalState: PropTypes.func.isRequired,
  setRemoveIntakeState: PropTypes.func.isRequired,
  newIntakeModalState: PropTypes.shape({
    id: PropTypes.string,
    visible: PropTypes.bool
  }),
  intakesState: PropTypes.shape({
    data: PropTypes.arrayOf(
      PropTypes.shape({
        id: PropTypes.string,
        name: PropTypes.string,
        campus: PropTypes.string,
        startDate: PropTypes.string,
        endDate: PropTypes.string,
        classSize: PropTypes.number
      })
    )
  })
}
export default React.memo(IntakeList)
