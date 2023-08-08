import * as React from 'react';
import {
  useEffect,
  useState
} from 'react';
import * as LoadState from "../../../App/Helper/LoadState";
import axios from "axios";
import Autocomplete from "@mui/material/Autocomplete";
import {
  CFormInput,
  CFormLabel
} from "@coreui/react";

const AutocompleteInput = ({value, dataUrl, disabled, onChange}) => {
  const [dataRequestTimerId, setDataRequestTimerId] = React.useState(null) // todo useRef
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
      onChange(newValue)
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

function fromStepDataToAutocompleteValue(data) {
  if (!data) {
    return null;
  }
  return {
    id: data.formValue,
    label: data.value
  }
}

function getCourseDisabledBasedOnSchool(school) {
  return !(school && school.id)
}

function getIntakeDisabledBasedOnCourse(course) {
  return !(course && course.id)
}

export default function ApplicationFirstStep({finishStep, blockStep, stepData, setStepData}) {
  const [intakesState, setIntakesState] = useState(LoadState.initialize())
  const [intakes, setIntakes] = React.useState([]);

  const [schoolValue, setSchoolValue] = React.useState(fromStepDataToAutocompleteValue(stepData.school));
  const [courseValue, setCourseValue] = React.useState(fromStepDataToAutocompleteValue(stepData.course));
  const [intakeValue, setIntakeValue] = React.useState(fromStepDataToAutocompleteValue(stepData.intake));
  const [courseDisabled, setCourseDisabled] = React.useState(getCourseDisabledBasedOnSchool(schoolValue));
  const [intakeDisabled, setIntakeDisabled] = React.useState(getIntakeDisabledBasedOnCourse(courseValue));
  useEffect(() => {
    if (stepData.school && stepData.course && stepData.intake) {
      finishStep()
    }
  }, [this])
  const onSchoolChange = (selectedSchool) => {
    setSchoolValue(selectedSchool)

    setCourseValue(null)
    setIntakeValue(null)
    blockStep()
    setIntakes([])
    setCourseDisabled(getCourseDisabledBasedOnSchool(selectedSchool))
  }
  let coursesUrl = null
  let intakesUrl = null
  if (schoolValue && schoolValue.id) {
    coursesUrl = window.abeApp.urls.api_student_application_course_list
      .replace(':schoolId', schoolValue.id)
  }
  const onCourseChange = (selectedCourse) => {
    setCourseValue(selectedCourse)

    setIntakeValue(null)
    setIntakes([]);
    blockStep()

    loadIntakes(selectedCourse, schoolValue)
    setIntakeDisabled(getIntakeDisabledBasedOnCourse(selectedCourse))
  }
  const loadIntakes = (course, school) => {
    if (course && course.id && school && school.id) {
      intakesUrl = window.abeApp.urls.api_student_application_intake_list
        .replace(':schoolId', school.id)
        .replace(':courseId', course.id)
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
  }
  const onIntakeChange = (event, newValue) => {
    setIntakeValue(newValue);
    if (!newValue) {
      blockStep()
    } else {
      setStepData({
        school: {formValue: schoolValue.id, value: schoolValue.label, title: 'School'},
        course: {formValue: courseValue.id, value: courseValue.label, title: 'Course'},
        intake: {formValue: newValue.id, value: newValue.label, title: 'Intake'},
      })
      finishStep()
    }
  }

  return <>
    <div className="mb-3">
      <CFormLabel htmlFor="school">Choose school</CFormLabel>
      <AutocompleteInput
        onChange={onSchoolChange}
        value={schoolValue}
        dataUrl={window.abeApp.urls.api_student_application_school_list}
      />
    </div>
    <div className="mb-3">
      <CFormLabel htmlFor="course">Choose course</CFormLabel>
      <AutocompleteInput
        disabled={courseDisabled}
        onChange={onCourseChange}
        value={courseValue}
        dataUrl={coursesUrl}
      />
    </div>
    <div className="mb-3">
      <CFormLabel htmlFor="intake">Choose intake</CFormLabel>
      <Autocomplete
        disabled={intakeDisabled}
        loading={intakesState.loading}
        onOpen={() => {
          if (!intakesState.loaded && !intakesState.loading) {
            loadIntakes(courseValue, schoolValue)
          }
        }}
        id="intakes"
        sx={{width: 600}}
        value={intakeValue}
        onChange={onIntakeChange}
        options={intakes}
        renderInput={RenderInput()}
      />
    </div>
  </>

}
