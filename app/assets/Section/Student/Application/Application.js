import * as React from 'react';
import {
  useEffect,
  useState
} from 'react';
import Autocomplete from '@mui/material/Autocomplete';
import {
  CCard,
  CCardBody,
  CCardHeader,
  CFormInput,
  CFormLabel
} from "@coreui/react";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";
import * as LoadState from "../../../App/Helper/LoadState";
import axios from "axios";

const AutocompleteInput = ({value, setValue, dataUrl, disabled}) => {
  const [dataRequestTimerId, setDataRequestTimerId] = React.useState(null)
  const [optionsState, setOptionsState] = useState(LoadState.initialize())

  const [options, setOptions] = React.useState([]);

  const loadOptions = (name) => {
    setOptionsState(LoadState.startLoading())
    axios.get(dataUrl, {params: {'filter[name]': name}})
      .then((response) => {
        setOptionsState(LoadState.finishLoading(response.data))
        let newOptions = [];
        if (response.data) {
          newOptions = response.data.map((s) => {
            return {label: s.name, id: s.id}
          })
        }
        setOptions(newOptions);
      })
      .catch((error) => {
        setOptionsState(LoadState.error(error.response?.data?.error))
      })
  }
  return <Autocomplete
    disabled={disabled}
    loading={dataRequestTimerId !== null || optionsState.loading}
    id={'id' + Math.random().toString()}
    sx={{width: 600}}
    filterOptions={(x) => x}
    options={options}
    isOptionEqualToValue={(option, value) => option.title === value.title}
    value={value}
    onChange={(event, newValue) => {
      setValue(newValue);
    }}
    onInputChange={(event, newInputValue) => {
      if (dataRequestTimerId !== null) {
        clearTimeout(dataRequestTimerId);
      }
      setOptions([]);
      const newOptionsRequestTimerId = setTimeout(() => {
        setDataRequestTimerId(null)
        if (!newInputValue || newInputValue.length < 2) {
          setOptions([]);
          return;
        }
        loadOptions(newInputValue);
      }, 1000)
      setDataRequestTimerId(newOptionsRequestTimerId)
    }}
    renderInput={RenderInput()}
  />
}
const RenderInput = () => {
  return (params) => {
    return <div ref={params.InputProps.ref}>
      <CFormInput {...params.inputProps}
      />
    </div>
  }
}

export default function Application() {
  const [intakesState, setIntakesState] = useState(LoadState.initialize())
  const [intakes, setIntakes] = React.useState([]);

  const [schoolValue, setSchoolValue] = React.useState(null);
  const [courseValue, setCourseValue] = React.useState(null);
  const [intakeValue, setIntakeValue] = React.useState(null);
  const [courseDisabled, setCourseDisabled] = React.useState(true);
  const [intakeDisabled, setIntakeDisabled] = React.useState(true);
  useEffect(() => {
    setCourseValue(null)
    setIntakeValue(null)
    setIntakes([]);

    if (schoolValue && schoolValue.id) {
      setCourseDisabled(false)
    }
  }, [schoolValue])
  let coursesUrl = null
  let intakesUrl = null
  if (schoolValue && schoolValue.id) {
    coursesUrl = window.abeApp.urls.api_student_application_course_list
      .replace(':schoolId', schoolValue.id)
  }
  useEffect(() => {
    setIntakeValue(null)
    setIntakes([]);

    if (courseValue && courseValue.id && schoolValue && schoolValue.id) {
      setIntakeDisabled(false)
      intakesUrl = window.abeApp.urls.api_student_application_intake_list
        .replace(':schoolId', schoolValue.id)
        .replace(':courseId', courseValue.id)
      setIntakesState(LoadState.startLoading())
      axios.get(intakesUrl)
        .then((response) => {
          setIntakesState(LoadState.finishLoading(response.data))
          let newIntakes = [];
          if (response.data) {
            newIntakes = response.data.map((s) => {
              let name = s.name === null ? '' : ', ' + s.name
              return {label: s.start + ' - ' + s.end + name, id: s.id}
            })
          }
          setIntakes(newIntakes);
        })
        .catch((error) => {
          setIntakesState(LoadState.error(error.response?.data?.error))
        })
    }
    if (!courseValue || !courseValue.id) {
      setIntakeDisabled(true)
    }
  }, [courseValue])

  const error = null

  return <>
    <CCard className="mb-4">
      <CCardHeader>
        <strong>
          Create new application
        </strong>
      </CCardHeader>
      <CCardBody>
        <AppErrorMessage error={error}/>
        <div className="mb-3">
          <CFormLabel htmlFor="school">Choose school</CFormLabel>
          <AutocompleteInput
            value={schoolValue}
            setValue={setSchoolValue}
            dataUrl={window.abeApp.urls.api_student_application_school_list}
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="course">Choose course</CFormLabel>
          <AutocompleteInput
            disabled={courseDisabled}
            value={courseValue}
            setValue={setCourseValue}
            dataUrl={coursesUrl}
          />
        </div>
        <div className="mb-3">
          <CFormLabel htmlFor="intake">Choose intake</CFormLabel>
          <Autocomplete
            disabled={intakeDisabled}
            loading={intakesState.loading}
            id="intakes"
            sx={{width: 600}}
            value={intakeValue}
            onChange={(event, newValue) => {
              setIntakeValue(newValue);
            }}
            options={intakes}
            renderInput={RenderInput()}
          />
        </div>
      </CCardBody>
    </CCard>
  </>
}
