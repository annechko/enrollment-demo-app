import axios from "axios";

export function submitForm({event, state, setState, url, formId, onSuccess, headers})
{
	event.preventDefault()
	if (state.loading)
	{
		return;
	}

	setState({
		loading: true,
		error: null
	})
	axios.post(url, document.getElementById(formId), {
		headers: headers || {
			'Content-Type': 'application/json'
		}
	})
		.then(response =>
		{
			setState({
				loading: false,
				error: null
			})
			if (onSuccess)
			{
				onSuccess(response)
			}
		})
		.catch((error) =>
		{
			setState({
				loading: false,
				error: error.response?.data?.error || 'Something went wrong'
			})
		});
}
