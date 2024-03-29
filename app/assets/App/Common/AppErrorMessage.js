import PropTypes from 'prop-types'
import React from 'react'

const AppErrorMessage = ({ error }) => {
  return (
    <>
      {
        (error)
          ? (
            <div className="alert alert-danger" data-testid="error-msg">
              {error}
            </div>
          )
          : ''
      }
    </>
  )
}
AppErrorMessage.propTypes = {
  error: PropTypes.string
}
export default React.memo(AppErrorMessage)
