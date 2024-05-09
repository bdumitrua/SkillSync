class ModelNotFoundException(Exception):
    def __init__(self, message="Model not found", status=404):
        self.message = message
        self.status = status
        super().__init__(self.message, self.status)


class InvalidRequestMethodException(Exception):
    def __init__(self, message="Invalid request method", status=405):
        self.message = message
        self.status = status
        super().__init__(self.message, self.status)
