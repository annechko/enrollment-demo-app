import * as React from 'react';
import {
  CButton,
  CCard,
  CCardBody,
  CCardHeader
} from "@coreui/react";
import AppErrorMessage from "../../../App/Common/AppErrorMessage";

const ApplicationFirstStep = React.lazy(() => import('./ApplicationFirstStep'))
const ApplicationSecondStep = React.lazy(() => import('./ApplicationSecondStep'))

export default function Application() {
  const [currentStep, setCurrentStep] = React.useState(0);
  const [nextStepButtonDisabled, setNextStepButtonDisabled] = React.useState(true);
  const [applicationData, setApplicationData] = React.useState({});

  const error = null

  const steps = [
    ApplicationFirstStep,
    ApplicationSecondStep
  ]

  const blockStep = () => {
    setNextStepButtonDisabled(true)
  }
  const finishStep = () => {
    setNextStepButtonDisabled(false)
  }
  const setStepData = (stepState) => {
    const newData = {...applicationData}
    newData[currentStep] = stepState
    setApplicationData(newData)
  }
  const onNextScreenClick = () => {
    const nextStep = currentStep + 1
    if (nextStepButtonDisabled || nextStep >= steps.length) {
      return;
    }
    setNextStepButtonDisabled(true)
    setCurrentStep(nextStep)
  }
  const onPreviousScreenClick = () => {
    if (currentStep <= 0) {
      return;
    }
    setNextStepButtonDisabled(true)
    const newStep = currentStep - 1
    setCurrentStep(newStep)
  }
  const StepComponent = steps[currentStep]
  const stepData = applicationData[currentStep] ?? {}
  const showPreviousStepBtn = currentStep > 0

  return <>
    <CCard className="mb-4">
      <CCardHeader>
        <strong>
          Create new application - step {currentStep + 1} / {steps.length}
        </strong>
      </CCardHeader>
      <CCardBody>
        <AppErrorMessage error={error}/>
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
          disabled={nextStepButtonDisabled}
          className=" pl-1 pr-1"
          onClick={onNextScreenClick}
        >{currentStep < steps.length - 1 ? 'Next' : 'Submit'}
        </CButton>
      </CCardBody>
    </CCard>
  </>
}
