import * as React from 'react'
import { useState } from 'react'
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader
} from '@coreui/react'
import AppErrorMessage from '../../../App/Common/AppErrorMessage'
import * as LoadState from '../../../App/Helper/LoadState'
import * as Api from '../../../App/Helper/Api'
import AppBackButton from '../../../App/Common/AppBackButton'
import { Link } from 'react-router-dom'
import AppDataLoader from '../../../App/Common/AppDataLoader'

const ApplicationFirstStep = React.lazy(async () => await import('./ApplicationFirstStep'))
const ApplicationSecondStep = React.lazy(async () => await import('./ApplicationSecondStep'))

export default function Application () {
  const [currentStep, setCurrentStep] = React.useState(0)
  const [applicationSubmitted, setApplicationSubmitted] = React.useState(false)
  const [nextStepButtonDisabled, setNextStepButtonDisabled] = React.useState(true)
  const [applicationData, setApplicationData] = React.useState({})
  const [applicationSubmitState, setApplicationSubmitState] = useState(LoadState.initialize())

  const error = applicationSubmitState.error ?? null

  const steps = [
    ApplicationFirstStep,
    ApplicationSecondStep
  ]

  const blockStep = () => {
    setNextStepButtonDisabled(true)
    setApplicationSubmitState(LoadState.initialize())
  }
  const finishStep = () => {
    setNextStepButtonDisabled(false)
  }
  const setStepData = (stepState) => {
    const newData = { ...applicationData }
    newData[currentStep] = stepState
    setApplicationData(newData)
  }
  const submitApplication = () => {
    const data = {}
    Object.values(applicationData).map(i => {
      Object.keys(i).map(j => {
        data[j] = i[j].formValue
      })
    })

    Api.submitData({
      state: applicationSubmitState,
      setState: setApplicationSubmitState,
      url: window.abeApp.urls.api_student_application,
      data,
      onSuccess: () => {
        setApplicationSubmitted(true)
      }
    })
  }
  const onNextScreenClick = () => {
    const nextStep = currentStep + 1
    if (nextStepButtonDisabled) {
      return
    }
    if (nextStep >= steps.length) {
      submitApplication()
    } else {
      setNextStepButtonDisabled(true)
      setCurrentStep(nextStep)
    }
  }
  const onPreviousScreenClick = () => {
    if (currentStep <= 0) {
      return
    }
    setNextStepButtonDisabled(true)
    const newStep = currentStep - 1
    setCurrentStep(newStep)
  }
  const StepComponent = steps[currentStep]
  const stepData = applicationData[currentStep] ?? {}
  const showPreviousStepBtn = currentStep > 0

  return <>
    <AppBackButton/>
    <CCard className="mb-4">
      <CCardHeader>
        <strong>
          Create new application - step {currentStep + 1} / {steps.length}
        </strong>
      </CCardHeader>
      <CCardBody>
        <AppErrorMessage error={error}/>
        {
          applicationSubmitted
            ? <SuccessSubmitMessage/>
            : <>
              <StepComponent
                stepData={stepData}
                setStepData={setStepData}
                finishStep={finishStep}
                blockStep={blockStep}
              />
              {
                showPreviousStepBtn &&
                <CButton color="primary" role="button"
                  variant="outline"
                  className=" pl-1 pr-1 me-3"
                  onClick={onPreviousScreenClick}
                >Back
                </CButton>
              }
              <CButton color="primary" role="button"
                disabled={nextStepButtonDisabled || applicationSubmitState.loading}
                className=" pl-1 pr-1"
                onClick={onNextScreenClick}
              >
                {applicationSubmitState.loading === true &&
                  <AppDataLoader/>}
                {currentStep < steps.length - 1 ? 'Next' : 'Submit'}
              </CButton>
            </>
        }
      </CCardBody>
    </CCard>
  </>
}
const SuccessSubmitMessage = () => {
  return <>
    <p>Your application was successfully submitted!</p>
    <p>You can check your application status on <Link
      to={window.abeApp.urls.student_application_list}>Applications</Link> page.</p>
  </>
}
